<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
        protected $primaryKey = 'id';
    protected $table = 'certificate';
    protected $guarded = ['id'];
}
