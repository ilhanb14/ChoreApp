<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Enums\FamilyRole;

class FamilyUser extends Pivot
{
    protected $casts = [
        'role' => FamilyRole::class
    ];
}