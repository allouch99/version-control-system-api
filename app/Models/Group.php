<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\File;
class Group extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'bg_image_url',
        'icon_image_url'
    ];
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function files():HasMany
    {
        return $this->hasMany(File::class,'group_id');
    }
}
