<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\Group;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'directory'
    ];

    protected function getPathAttribute()
    {
        return $this->directory . $this->name;
    }
    protected function getTemporaryUrlAttribute()
    {
        return Storage::temporaryUrl($this->path,now()->addMinutes(5));
    }
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
