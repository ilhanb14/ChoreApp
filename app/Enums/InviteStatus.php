<?php

namespace App\Enums;

enum InviteStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Denied = 'denied';
}
