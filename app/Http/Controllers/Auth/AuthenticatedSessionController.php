<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use jeemce\helpers\DBHelper;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login-custom');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // generate otp
        $sendOtp = OtpHelper::sendOtpForUser(Auth::id());
        if (!$sendOtp) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->back()->withErrors(['email' => 'Gagal mengirim kode OTP, silahkan coba lagi.']);
        }

        return redirect()->intended(route('otp.input', absolute: false));
    }

    /**
     * otp input page
     */
    public function otpInput(): View
    {
        return view('auth.otp-input');
    }

    /**
     * otp verify
     */
    public function otpVerify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:6|min:6',
        ]);

        $validOtp = OtpHelper::isValidOtp(Auth::id(), $request->code);
        if (!$validOtp) {
            return redirect()->back()->withErrors(['kode' => 'Kode OTP tidak valid / sudah kadaluarsa']);
        }

        OtpHelper::markOtpAsUsed(Auth::id(), $request->code);

        return redirect()->intended(route('backend.dashboard', absolute: false));
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
