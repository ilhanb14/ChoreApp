<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Family;

class Reward extends Model
{
    protected $fillable = [
        'reward',
        'points',
        'family_id',
        'claim_type'
    ];

    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function usersClaimed() {
        return $this->belongsToMany(User::class, 'reward_user', 'reward_id', 'user_id');
    }
}
