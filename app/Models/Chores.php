<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chores extends Model
{

    protected $fillable = [
        'title',
        'description',
        'points',
        'assigned_to',
        'due_date',
        'frequency',
    ];
}
