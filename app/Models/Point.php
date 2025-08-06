<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{

    protected $guarded = ['id'];
    protected $table = 'points';

    public function pointable()
    {
        return $this->morphTo();
    }
}
