<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id')
            ->withTimestamps();
    }
}
