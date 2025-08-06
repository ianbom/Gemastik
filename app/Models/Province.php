<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = ['id'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get the users for the province.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
