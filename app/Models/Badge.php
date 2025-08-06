<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the users that have this badge.
     */
   public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }

    /**
     * The communities that have this badge.
     */
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_badges');
    }
}
