<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $guarded = ['id'];

    protected $table = 'merchandise';

    public function reedems()
    {
        return $this->hasMany(Reedems::class);
    }
}
