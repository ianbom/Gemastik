<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = ['id'];
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the districts for the city.
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get the users for the city.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the reports for the city.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get the missions for the city.
     */
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
