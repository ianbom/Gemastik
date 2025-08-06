<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['name', 'description', 'social_media', 'user_id', 'member_count'];

    protected $casts = [
        'social_media' => 'array',
    ];

    /**
     * Get the user who created the community.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The badges that belong to the community.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'community_badges');
    }

    /**
     * The missions that the community is assigned to.
     */
    public function missions()
    {
        return $this->belongsToMany(Mission::class, 'mission_communities')
            ->withPivot('status', 'answered_at', 'certificate_url', 'awarded_at')
            ->withTimestamps();
    }
}
