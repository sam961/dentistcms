<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // First, validate credentials
        $credentials = $request->only('email', 'password');
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (! $user || ! \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Check if email is verified
        if (! $user->hasVerifiedEmail()) {
            // Send verification code
            $verificationCode = \App\Models\VerificationCode::createFor(
                $user,
                \App\Models\VerificationCode::TYPE_EMAIL_VERIFICATION,
                60
            );

            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\VerificationCodeMail($verificationCode));

            return redirect()->route('verification.show', ['email' => $user->email])
                ->with('info', 'Please verify your email address first. A verification code has been sent to your email.');
        }

        // Send 2FA code
        $verificationCode = \App\Models\VerificationCode::createFor(
            $user,
            \App\Models\VerificationCode::TYPE_LOGIN_2FA,
            15 // 15 minutes for login codes
        );

        \Illuminate\Support\Facades\Mail::to($user->email)
            ->send(new \App\Mail\VerificationCodeMail($verificationCode));

        // Store user ID in session for 2FA verification
        $request->session()->put('2fa_user_id', $user->id);
        $request->session()->put('2fa_remember', $request->boolean('remember'));

        return redirect()->route('login.2fa.show')
            ->with('success', 'A verification code has been sent to your email.');
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
