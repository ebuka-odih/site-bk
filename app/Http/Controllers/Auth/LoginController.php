<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return inertia('Auth/Login');
    }

    /**
     * Step 1: Validate email and send OTP
     */
    public function sendOtp(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Redirect admins to admin login page
        if ($user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => 'Admin users should use the admin login page.',
            ])->redirectTo('/admin/login');
        }

        // Generate and send OTP
        $otpCode = OtpCode::generateForEmail($request->email);
        
        // Send OTP via email
        Mail::to($request->email)->send(new OtpCodeMail($otpCode->code, $user->name));

        // Store user name in session for the next step
        session(['login_user_name' => $user->name, 'login_user_email' => $request->email]);

        return back()->with([
            'success' => 'OTP code sent to your email',
            'user_name' => $user->name,
        ]);
    }

    /**
     * Resend OTP code
     */
    public function resendOtp(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Delete any existing OTP codes for this email
        OtpCode::where('email', $request->email)->delete();

        // Generate and send new OTP
        $otpCode = OtpCode::generateForEmail($request->email);
        
        // Send OTP via email
        Mail::to($request->email)->send(new OtpCodeMail($otpCode->code, $user->name));

        return back()->with([
            'success' => 'New OTP code sent to your email',
        ]);
    }

    /**
     * Step 2: Verify OTP and password
     */
    public function verifyOtpAndLogin(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Verify OTP code - Get the most recent OTP for this email
        $otpCode = OtpCode::where('email', $request->email)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpCode) {
            throw ValidationException::withMessages([
                'otp_code' => 'No OTP code found. Please request a new code.',
            ]);
        }

        if ($otpCode->isExpired()) {
            throw ValidationException::withMessages([
                'otp_code' => 'OTP code has expired. Please request a new code.',
            ]);
        }

        if ($otpCode->isUsed()) {
            throw ValidationException::withMessages([
                'otp_code' => 'OTP code has already been used. Please request a new code.',
            ]);
        }

        if ($otpCode->hasExceededMaxAttempts()) {
            throw ValidationException::withMessages([
                'otp_code' => 'Too many failed attempts. Please request a new code.',
            ]);
        }

        if (!$otpCode->verify($request->otp_code)) {
            throw ValidationException::withMessages([
                'otp_code' => 'Invalid OTP code. Please try again.',
            ]);
        }

        // Verify password
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'password' => 'Invalid password.',
            ]);
        }

        $request->session()->regenerate();
        
        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }
        
        return redirect()->intended('/dashboard');
    }

    /**
     * Legacy login method for backward compatibility
     */
    public function login(Request $request)
    {
        $this->enforceBotProtection($request);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
