<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
class Group extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
