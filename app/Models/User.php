<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Group;
use App\Models\Invitation;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'role'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole()
    {
        //return $this->hasMany(Invitation::class,'sent_id');
    }

    public function groups():HasMany
    {
        return $this->hasMany(Group::class,'user_id');
    }

    public function invitations(): BelongsToMany
    {
        return $this->belongsToMany(Group::class,'invitations')
                ->as('invitation')
                ->withPivot('description', 'role','status');
    }
    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Group::class,'memberships')
                ->as('membership')
                ->withPivot('role');
    }

    public function setInvitations(): HasMany
    {
        return $this->hasMany(Invitation::class,'sent_id');
    }

}
