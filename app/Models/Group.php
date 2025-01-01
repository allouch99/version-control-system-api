<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
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
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function files():HasMany
    {
        return $this->hasMany(File::class,'group_id');
    }
}
