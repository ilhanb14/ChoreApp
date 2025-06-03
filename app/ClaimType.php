<?php

namespace App;

enum ClaimType: string
{
    case Single = 'single';
    case PerUser = 'per_user';
    case Repeat = 'repeat';
}
