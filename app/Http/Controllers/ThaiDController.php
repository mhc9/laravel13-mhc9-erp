<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\ThaiDService;
use App\Models\User;
use App\Models\Employee;

class ThaiDController extends Controller
{
    public function redirect(Request $request, ThaiDService $thaiDService): RedirectResponse
    {
        return redirect($thaiDService->authorizationUrl($request));
    }

    public function callback(Request $request, ThaiDService $thaiD): RedirectResponse
    {
        /**
         *  ต้องตรวจ state, nonce, signature / ID token / code exchange
         *  ภายใน service ตามเอกสารและ credential ที่ DGA/DOPA ออกให้
         */
        $verifiedIdentity = $thaiD->verifyCallback($request);

        // Find a user based on pid that retrieved from ThaiD Service
        $employee = Employee::where('pid', $verifiedIdentity->pid)->first();

        // นโยบายแนะนำ: ให้ผู้ดูแลผูก ThaiD กับบัญชีไว้ก่อน
        abort_unless($employee?->user, 403, 'บัญชี ThaID นี้ยังไม่ได้รับสิทธิ์ใช้งานระบบ');

        // Tell Laravel to log this transient object into the session
        auth()->guard('web')->login($employee?->user);

        // Regenerate the session ID for security
        $request->session()->regenerate();

        // Redirect กลับไป frontend
        $redectUrl = $request->session()->get('passport_redirect_uri', '/');

        return redirect()->intended($redectUrl);
    }
}