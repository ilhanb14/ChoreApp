<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Family;
use App\Enums\FamilyRole;
use App\Enums\InviteStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Invite extends Model
{
    protected $fillable = ['family_id', 'inviter_id', 'invited_id', 'role', 'status'];

    protected $casts = [
        'role' => FamilyRole::class,
        'status' => InviteStatus::class,
    ];

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
    
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
    
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }
    
    public function invited(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_id');
    }
    
    public function render()
    {
        return view('livewire.invites.index');
    }
}
