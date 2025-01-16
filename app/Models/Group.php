<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\File;
class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'description',
        'bg_image_url',
        'icon_image_url'
    ];

    protected function filesDirectory(): Attribute
    {
        return Attribute::make(
            get: fn () =>  (
                'files/' . $this->user['user_name'] . '/' . $this['name'] . '/'
            ) 
        );
    }
    protected function imagesDirectory(): Attribute
    {
        return Attribute::make(
            get: fn () =>  (
                'images/' . $this->user['user_name'] . '/' . $this['name'] . '/'
            ) 
        );
    }
    protected function bgImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (string |null $value) =>  ($value) ? Storage::temporaryUrl(
                $value, now()->addMinutes(5) 
            ): null 
        );
    }
    protected function iconImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (string |null $value) => ($value) ? Storage::temporaryUrl(
                $value, now()->addMinutes(5) 
            ): null
        );
    }
    protected function bgImageOriginalUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->attributes['bg_image_url'])
        );
    }
    protected function iconImageOriginalUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->attributes['icon_image_url'])
        );
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function files():HasMany
    {
        return $this->hasMany(File::class,'group_id');
    }
    public function invitations():HasMany
    {
        return $this->hasMany(Invitation::class,'group_id');
    }

    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'memberships')
                ->as('membership')
                ->withPivot('role');
    }
}


