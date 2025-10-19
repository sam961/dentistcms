<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case SCHEDULED = 'scheduled';
    case CONFIRMED = 'confirmed';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Scheduled',
            self::CONFIRMED => 'Confirmed',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::NO_SHOW => 'No Show',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SCHEDULED => 'blue',
            self::CONFIRMED => 'green',
            self::IN_PROGRESS => 'yellow',
            self::COMPLETED => 'purple',
            self::CANCELLED => 'red',
            self::NO_SHOW => 'gray',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SCHEDULED => 'calendar',
            self::CONFIRMED => 'check-circle',
            self::IN_PROGRESS => 'clock',
            self::COMPLETED => 'check-double',
            self::CANCELLED => 'times-circle',
            self::NO_SHOW => 'user-times',
        };
    }
}
