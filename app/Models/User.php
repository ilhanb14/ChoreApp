<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Family;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Models\FamilyUser;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function families()
    {
        return $this->belongsToMany(Family::class, 'family_user', 'user_id', 'family_id')
            ->withPivot(['role', 'points'])
            ->withTimestamps()
            ->using(FamilyUser::class);
    }

    public function invites()
    {
        return $this->hasMany(Invite::class, 'invited_id');
    }

    public function isParentIn(Family $family): bool
    {
        return $this->families()
            ->where('family_id', $family->id)
            ->wherePivot('role', 'parent')
            ->exists();
    }

    // Only verified users on our domain can access filament
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@chorebusters.be') && $this->hasVerifiedEmail();
    }

    public function chores()
    {
        return $this->belongsToMany(Chores::class, 'task_user', 'user_id', 'task_id')
            ->withPivot(['assigned_by', 'performed', 'confirmed', 'comment'])
            ->withTimestamps();
    }
}
