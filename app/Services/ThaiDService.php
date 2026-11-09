<?php

namespace App\Services;

use Illuminate\Http\Request;

class ThaiDService
{
    public function authorizationUrl(Request $request): string
    {
        $state = bin2hex(random_bytes(32));
        $nonce = bin2hex(random_bytes(32));

        $request->session()->put('thaid.state', $state);
        $request->session()->put('thaid.nonce', $nonce);
        $scope = 'pid title given_name family_name';

        $query = http_build_query([
            'version'       => 2,
            'client_id'     => env('THAID_CLIENT_ID'),
            'redirect_uri'  => env('THAID_CLIENT_CALLBACK'),
            'response_type' => 'code',
            'scope'         => $scope,
            'state'         => $state,
            'nonce'         => $nonce,
        ]);

        return env('THAID_SERVER_URI') . '/oauth2/auth/?' . $query;
    }

    public function verifyCallback(Request $request): object
    {
        abort_unless(
            hash_equals(
                (string) $request->session()->pull('thaid.state'),
                (string) $request->string('state')
            ),
            403,
            'Invalid ThaiD state'
        );

        // 1. แลก authorization code เป็น token แบบ server-to-server
        // 2. ตรวจ issuer, audience, expiry, nonce และลายเซ็น/JWKS
        // 3. ดึง subject ที่ผ่านการยืนยันแล้ว
        // คืน object อย่างน้อย { subject: string }
        $response = \Illuminate\Support\Facades\Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode(env('THAID_CLIENT_ID') . ':' . env('THAID_CLIENT_SECRET')),
            ])
            ->post(env('THAID_SERVER_URI') . '/oauth2/token/', [
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => env('THAID_CLIENT_CALLBACK'),
                'code'          => $request->input('code'),
            ]);

        return (object) $response->json();

        throw new \LogicException('Implement ตาม ThaiD integration specification');
    }
}
