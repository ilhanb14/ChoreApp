<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chores extends Model
{
   
    protected $table = 'tasks';

    // Fillable fields for mass assignment
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

    // Many-to-many relationship with users via 'task_user' pivot table
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id',)
                    ->withPivot(['performed', 'comment', 'assigned_by', 'confirmed'])
                    ->withTimestamps();
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
