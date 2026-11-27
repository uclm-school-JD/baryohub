<?php

namespace App\Enums;

enum DocumentRequestStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case ReadyForPickup = 'ready_for_pickup';

    case Approved = 'approved';
    case Rejected = 'rejected';
    case Released = 'released';

    public function label(): string
    {
        return match ($this) {
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Released => 'Released',
            self::Pending => 'Pending',
            self::InProgress => 'In progress',
            self::ReadyForPickup => 'Ready for pickup',
        };
    }
}
