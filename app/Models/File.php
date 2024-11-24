<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Group;
class File extends Model
{
    protected $fillable = [
        'name',
        'directory'
    ];

    public function group():BelongsTo
    {
        return $this->belongsTo(Group::class,'group_id');
    }
}
