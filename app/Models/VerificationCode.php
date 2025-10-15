<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'type',
        'expires_at',
        'is_used',
        'used_at',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    // Constants for code types
    const TYPE_EMAIL_VERIFICATION = 'email_verification';

    const TYPE_LOGIN_2FA = 'login_2fa';

    /**
     * Get the user that owns the verification code
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the code is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the code is valid (not used and not expired)
     */
    public function isValid(): bool
    {
        return ! $this->is_used && ! $this->isExpired();
    }

    /**
     * Mark the code as used
     */
    public function markAsUsed(?string $ipAddress = null): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }

    /**
     * Generate a random 6-digit code
     */
    public static function generateCode(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a secure token for email verification links
     */
    public static function generateToken(): string
    {
        return \Illuminate\Support\Str::random(64);
    }

    /**
     * Create a new verification code for a user
     */
    public static function createFor(User $user, string $type, int $expiresInMinutes = 15): self
    {
        // Invalidate any existing unused codes of the same type
        static::where('user_id', $user->id)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // For email verification, use a secure token instead of a 6-digit code
        $code = $type === static::TYPE_EMAIL_VERIFICATION
            ? static::generateToken()
            : static::generateCode();

        // Create new code
        return static::create([
            'user_id' => $user->id,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }

    /**
     * Verify a code for a user
     */
    public static function verify(User $user, string $code, string $type): bool
    {
        $verificationCode = static::where('user_id', $user->id)
            ->where('code', $code)
            ->where('type', $type)
            ->where('is_used', false)
            ->first();

        if (! $verificationCode || ! $verificationCode->isValid()) {
            return false;
        }

        $verificationCode->markAsUsed();

        return true;
    }
}
