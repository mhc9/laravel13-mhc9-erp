<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class MojoAuthService
{
    public function authorizationUrl(Request $request): string
    {
        $request->session()->put('oauth.state', $state = Str::random(40));
        $request->session()->put('code_verifier', $codeVerifier = Str::random(128));

        $codeChallenge = strtr(rtrim(base64_encode(hash('sha256', $codeVerifier, true)), '='), '+/', '-_');

        $query = http_build_query([
            'client_id'     => env('MOJOAUTH_CLIENT_ID'),
            'redirect_uri'  => env('MOJOAUTH_CLIENT_CALLBACK'),
            'response_type' => 'code',
            'scope'         => "openid profile email",
            'state'         => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return env('MOJOAUTH_ISSUER_URL') . '/oauth/authorize?' . $query;
    }

    public function verifyCallback(Request $request): object
    {
        $state = (string) $request->session()->pull('oauth.state');
        $codeVerifier = $request->session()->pull('code_verifier');

        abort_unless(
            strlen($state) > 0 && $state === (string) $request->string('state'),
            403,
            'Invalid Keycloak state'
        );

        // 1. แลก authorization code เป็น token แบบ server-to-server
        // 2. ตรวจ issuer, audience, expiry, nonce และลายเซ็น/JWKS
        // 3. ดึง subject ที่ผ่านการยืนยันแล้ว
        // คืน object อย่างน้อย { subject: string }
        $response = Http::post(env('MOJOAUTH_ISSUER_URL') . '/oauth2/token', [
                'grant_type'    => 'authorization_code',
                'client_id'     => env('MOJOAUTH_CLIENT_ID'),
                'redirect_uri'  => env('MOJOAUTH_CLIENT_CALLBACK'),
                'code_verifier' => $codeVerifier,
                'code'          => $request->input('code'),
            ]);

        return (object) $response->json();

        throw new \LogicException('Implement ตาม Keycloak integration specification');
    }

    public function getUserInfo(string $token): object
    {
        $response = Http::withToken($token)->get(env('MOJOAUTH_ISSUER_URL') . '/oauth/userinfo');

        return (object) $response->json();
    }
}
