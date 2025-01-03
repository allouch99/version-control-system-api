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
        'recipient_id',
        'group_id',
        'sent_id',
        'role',
        'description',
    ];

    public function sentUser()
    {
        return $this->belongsTo(User::class,'sent_id');
    } 
    public function receivedUser()
    {
        return $this->belongsTo(User::class,'recipient_id');
    } 
    public function group()
    {
        return $this->belongsTo(Group::class,'group_id');
    } 
}
