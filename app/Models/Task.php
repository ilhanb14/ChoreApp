<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'title',
        'description',
        'points',
        'assigned_to',
        'due_date',
        'frequency',
        'recurring',
        'family_id',
        'description',
    ];
}
