<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chores extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'name',
        'description',
        'family_id',
        'points',
        'recurring',
        'frequency',
        'start_date',
        'deadline',
        'created_by',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id')
            ->withPivot(['performed', 'comment', 'confirmed', 'assigned_by'])
            ->withTimestamps();
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
