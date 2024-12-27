<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Group;
class Invitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'group_id',
        'sent_id',
        'role',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    } 
    public function group()
    {
        return $this->belongsTo(Group::class,'group_id');
    } 
}
