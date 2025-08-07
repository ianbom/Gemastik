<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    protected $guarded = ['id'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function mission(){
        return $this->belongsTo(Mission::class);
    }
}
