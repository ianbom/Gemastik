<?php

namespace App\Models;

use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Get the report associated with the mission.
     */

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the user who created the mission.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    /**
     * Get the city where the mission takes place.
     */

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the district (district) where the mission takes place.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the volunteer user assigned to this mission (if assigned to a single volunteer).
     */
    public function assignedVolunteer()
    {
        return $this->belongsTo(User::class, 'assigned_volunteer_id');
    }

    /**
     * The communities assigned to this mission.
     */
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'mission_communities')
            ->withPivot('status', 'answered_at', 'certificate_url', 'awarded_at')
            ->withTimestamps();
    }

    /**
     * The volunteers participating in this mission.
     */
    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'mission_volunteers')
            ->withPivot('participation_status', 'is_leader', 'certificate_url', 'awarded_at')
            ->withTimestamps();
    }

    /**
     * Get the documentation for the mission.
     */
    public function documentation()
    {
        return $this->hasMany(MissionDocumentation::class);
    }
    public function confirmedLeader()
    {
        return $this->volunteers()
            ->wherePivot('is_leader', true)
            ->wherePivot('participation_status', '!=', 'pending')
            ->first();
    }
}
