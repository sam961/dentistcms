<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA verification form
     */
    public function show(Request $request)
    {
        // Check if user is in 2FA flow
        if (! $request->session()->has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Invalid 2FA request.');
        }

        $userId = $request->session()->get('2fa_user_id');
        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget(['2fa_user_id', '2fa_remember']);

            return redirect()->route('login')->with('error', 'User not found.');
        }

        return view('auth.verify-2fa', ['email' => $user->email]);
    }

    /**
     * Verify the 2FA code and complete login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        // Check if user is in 2FA flow
        if (! $request->session()->has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Invalid 2FA request.');
        }

        $userId = $request->session()->get('2fa_user_id');
        $remember = $request->session()->get('2fa_remember', false);

        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget(['2fa_user_id', '2fa_remember']);

            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Verify the 2FA code
        if (! VerificationCode::verify($user, $request->code, VerificationCode::TYPE_LOGIN_2FA)) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Clear 2FA session data
        $request->session()->forget(['2fa_user_id', '2fa_remember']);

        // Log the user in
        Auth::login($user, $remember);

        $request->session()->regenerate();

        // Redirect based on user role
        // Don't use intended() to avoid redirecting to wrong dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Resend 2FA code
     */
    public function resend(Request $request)
    {
        if (! $request->session()->has('2fa_user_id')) {
            return redirect()->route('login')->with('error', 'Invalid 2FA request.');
        }

        $userId = $request->session()->get('2fa_user_id');
        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget(['2fa_user_id', '2fa_remember']);

            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Create new 2FA code
        $verificationCode = VerificationCode::createFor(
            $user,
            VerificationCode::TYPE_LOGIN_2FA,
            15
        );

        // Send email
        \Illuminate\Support\Facades\Mail::to($user->email)
            ->send(new \App\Mail\VerificationCodeMail($verificationCode));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
