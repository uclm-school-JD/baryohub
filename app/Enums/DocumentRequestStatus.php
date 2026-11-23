<?php

namespace App\Enums;

enum DocumentRequestStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case ReadyForPickup = 'ready_for_pickup';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In progress',
            self::ReadyForPickup => 'Ready for pickup',
        };
    }
}
