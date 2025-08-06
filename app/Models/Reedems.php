<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reedems extends Model
{
    protected $guarded = ['id'];
    protected $table = 'redeems';

    public function points()
    {
        // Parameter kedua ('pointable') harus cocok dengan nama yang Anda gunakan di `morphs()`
        return $this->morphMany(Point::class, 'pointable');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

     public function merchandise(){
        return $this->belongsTo(Merchandise::class);
    }
}
