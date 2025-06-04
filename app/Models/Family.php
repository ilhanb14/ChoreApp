<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [ 'name' ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'family_user', 'family_id', 'user_id');
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }
}
