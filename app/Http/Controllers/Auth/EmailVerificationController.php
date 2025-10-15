<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Verify email via token link
     */
    public function verify(Request $request, $token)
    {
        // Find the verification code by token
        $verificationCode = VerificationCode::where('code', $token)
            ->where('type', VerificationCode::TYPE_EMAIL_VERIFICATION)
            ->where('is_used', false)
            ->first();

        if (! $verificationCode || ! $verificationCode->isValid()) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired verification link. Please request a new one.');
        }

        $user = $verificationCode->user;

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Mark token as used
        $verificationCode->markAsUsed();

        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now log in.');
    }

    /**
     * Resend verification link
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['email' => 'User not found.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('info', 'Email is already verified.');
        }

        // Create new verification token
        $verificationCode = VerificationCode::createFor(
            $user,
            VerificationCode::TYPE_EMAIL_VERIFICATION,
            1440 // 24 hours
        );

        // Send email
        \Illuminate\Support\Facades\Mail::to($user->email)
            ->send(new \App\Mail\VerificationCodeMail($verificationCode));

        return redirect()->route('login')
            ->with('success', 'A new verification link has been sent to your email.');
    }
}
