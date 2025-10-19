<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case ACTIVE = 'active';
    case TRIAL = 'trial';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::TRIAL => 'Trial',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::TRIAL => 'blue',
            self::EXPIRED => 'red',
            self::CANCELLED => 'gray',
            self::SUSPENDED => 'yellow',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE || $this === self::TRIAL;
    }
}
