<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification form
     */
    public function show(Request $request)
    {
        // Get email from query parameter or session
        $email = $request->query('email') ?? $request->session()->get('verification_email');

        if (! $email) {
            return redirect()->route('login')->with('error', 'Invalid verification request.');
        }

        $request->session()->put('verification_email', $email);

        return view('auth.verify-email-code', compact('email'));
    }

    /**
     * Verify the email code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Verify the code
        if (! VerificationCode::verify($user, $request->code, VerificationCode::TYPE_EMAIL_VERIFICATION)) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Clear the session
        $request->session()->forget('verification_email');

        return redirect()->route('login')->with('success', 'Email verified successfully! You can now log in.');
    }

    /**
     * Resend verification code
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email is already verified.');
        }

        // Create new verification code
        $verificationCode = VerificationCode::createFor(
            $user,
            VerificationCode::TYPE_EMAIL_VERIFICATION,
            60
        );

        // Send email
        \Illuminate\Support\Facades\Mail::to($user->email)
            ->send(new \App\Mail\VerificationCodeMail($verificationCode));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
