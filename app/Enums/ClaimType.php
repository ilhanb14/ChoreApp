<?php

namespace App\Enums;

enum ClaimType: string
{
    case Single = 'single';
    case PerUser = 'per_user';
    case Repeat = 'repeat';


    public function label(): string
    {
        return match($this) {
            self::Single => 'Single',
            self::PerUser => 'Per user',
            self::Repeat => 'Repeat',
        };
    }

}