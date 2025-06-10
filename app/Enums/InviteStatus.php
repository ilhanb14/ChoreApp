<?php

namespace App\Enums;

enum InviteStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'denied';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending Review',
            self::Accepted => 'Accepted',
            self::Declined => 'Declined',
        };
    }
}
