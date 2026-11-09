<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\ThaiDService;
use App\Models\User;
use App\Models\Staff;

class ThaiDController extends Controller
{
    public function redirect(Request $request, ThaiDService $thaiDService): RedirectResponse
    {
        return redirect($thaiDService->authorizationUrl($request));
    }

    public function callback(Request $request, ThaiDService $thaiD): RedirectResponse
    {
        // ต้องตรวจ state, nonce, signature / ID token / code exchange
        // ภายใน service ตามเอกสารและ credential ที่ DGA/DOPA ออกให้
        $verifiedIdentity = $thaiD->verifyCallback($request);

        // find a user based on pid that retrieved from ThaiD Service
        $staff = Staff::where('pid', $verifiedIdentity->pid)->first();

        // นโยบายแนะนำ: ให้ผู้ดูแลผูก ThaiD กับบัญชีไว้ก่อน
        abort_unless($staff?->user, 403, 'บัญชี ThaID นี้ยังไม่ได้รับสิทธิ์ใช้งานระบบ');

        auth()->guard('web')->login($staff->user);
        $request->session()->regenerate();

        // ถ้าเริ่มต้นจาก /oauth/authorize จะกลับไปจุดนั้น
        return redirect()->intended('/');
    }
}