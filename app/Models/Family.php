<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use app\Models\User;

class Family extends Model
{
    protected $fillable = [ 'name' ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'family_user', 'family_id', 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role'])
            ->withTimestamps()
            ->using(function ($model) {
                $model->casts = [
                    'role' => FamilyRole::class
                ];
                return $model;
            });
    }

    public function adults()
    {
        return $this->users()->wherePivot('role', 'adult');
    }

    public function children()
    {
        return $this->users()->wherePivot('role', 'child');
    }
}
