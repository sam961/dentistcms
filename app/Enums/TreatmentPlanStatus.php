<?php

namespace App\Enums;

enum TreatmentPlanStatus: string
{
    case DRAFT = 'draft';
    case PRESENTED = 'presented';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PRESENTED => 'Presented',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PRESENTED => 'blue',
            self::ACCEPTED => 'green',
            self::REJECTED => 'red',
            self::IN_PROGRESS => 'yellow',
            self::COMPLETED => 'purple',
            self::CANCELLED => 'red',
        };
    }
}
