<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\MojoAuthService;
use App\Models\User;
use App\Models\Staff;

class MojoAuthController extends Controller
{
    public function redirect(Request $request, MojoAuthService $mojoAuthService): RedirectResponse
    {
        return redirect($mojoAuthService->authorizationUrl($request));
    }

    public function callback(Request $request, MojoAuthService $mojoAuthService): RedirectResponse
    {
        // ต้องตรวจ state / ID token / code exchange ภายใน service credential ที่ MojoAuth ออกให้
        $verifiedIdentity = $mojoAuthService->verifyCallback($request);

        // ดึงข้อมูลผู้ใช้จาก MojoAuth ด้วย access token
        $userInfo = $mojoAuthService->getUserInfo($verifiedIdentity->access_token);

        // Find a user based on email that retrieved from MojoAuth Service
        $user = User::where('email', $userInfo->email)->first();

        // นโยบายแนะนำ: ให้ผู้ดูแลผูก MojoAuth กับบัญชีไว้ก่อน
        abort_unless($user, 403, 'บัญชี MojoAuth นี้ยังไม่ได้รับสิทธิ์ใช้งานระบบ');

        // Tell Laravel to log this transient object into the session
        auth()->guard('web')->login($user);

        // Regenerate the session ID for security
        $request->session()->regenerate();

        // Redirect กลับไป frontend
        $redectUrl = $request->session()->get('passport_redirect_uri', '/');

        return redirect()->intended($redectUrl);
    }
}