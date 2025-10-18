<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    /**
     * Boot the model and configure cascade deletes
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Tenant $tenant) {
            // Delete all related records when tenant is deleted
            $tenant->users()->delete();
            $tenant->patients()->delete();
            $tenant->dentists()->delete();
            $tenant->appointments()->delete();
            $tenant->invoices()->delete();
            $tenant->subscriptionHistory()->delete();
        });
    }

    protected $fillable = [
        'name',
        'subdomain',
        'domain',
        'owner_name',
        'owner_email',
        'owner_phone',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'status',
        'trial_ends_at',
        'subscription_plan',
        'max_users',
        'max_patients',
        'max_dentists',
        'logo_path',
        // Subscription fields
        'subscription_status',
        'subscription_tier',
        'subscription_starts_at',
        'subscription_ends_at',
        'next_billing_date',
        'auto_renew',
        'scheduled_tier',
        'scheduled_change_date',
        'scheduled_change_reason',
        'grace_period_ends_at',
        'last_payment_date',
        'payment_status',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'max_users' => 'integer',
        'max_patients' => 'integer',
        'max_dentists' => 'integer',
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'next_billing_date' => 'datetime',
        'auto_renew' => 'boolean',
        'scheduled_change_date' => 'datetime',
        'grace_period_ends_at' => 'datetime',
        'last_payment_date' => 'datetime',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function dentists()
    {
        return $this->hasMany(Dentist::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function subscriptionHistory()
    {
        return $this->hasMany(TenantSubscriptionHistory::class)->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the full URL for this tenant
     *
     * @param  string|null  $path  Optional path to append
     * @param  bool  $secure  Use HTTPS
     */
    public function getUrl(?string $path = null, bool $secure = false): string
    {
        $protocol = $secure || config('app.env') === 'production' ? 'https' : 'http';
        $appDomain = config('app.domain', 'dentistcms.test');
        $url = "{$protocol}://{$this->subdomain}.{$appDomain}";

        if ($path) {
            $url .= '/'.ltrim($path, '/');
        }

        return $url;
    }

    /**
     * Get the dashboard URL for this tenant
     */
    public function getDashboardUrlAttribute(): string
    {
        return $this->getUrl('/dashboard');
    }

    /**
     * Get subscription tier configuration
     */
    public function getTierConfig(): ?array
    {
        return config("subscription.tiers.{$this->subscription_tier}");
    }

    /**
     * Get current subscription price
     */
    public function getSubscriptionPrice(): float
    {
        return (float) config("subscription.tiers.{$this->subscription_tier}.price", 0);
    }

    /**
     * Check if subscription is expired
     */
    public function isSubscriptionExpired(): bool
    {
        if (! $this->subscription_ends_at) {
            return false; // Free tier never expires
        }

        return $this->subscription_ends_at->isPast();
    }

    /**
     * Check if in grace period
     */
    public function isInGracePeriod(): bool
    {
        return $this->grace_period_ends_at && $this->grace_period_ends_at->isFuture();
    }

    /**
     * Check if subscription is active (including grace period)
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active' || $this->isInGracePeriod();
    }

    /**
     * Check if subscription has scheduled change
     */
    public function hasScheduledChange(): bool
    {
        return ! is_null($this->scheduled_tier);
    }

    /**
     * Get days until subscription ends
     */
    public function daysUntilExpiration(): ?int
    {
        if (! $this->subscription_ends_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->subscription_ends_at, false));
    }
}
