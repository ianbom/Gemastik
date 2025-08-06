<?php

namespace App\Models;

use App\Models\User;
use App\Models\Province;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'verified_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function points()
    {
        // Parameter kedua ('pointable') harus cocok dengan nama yang Anda gunakan di `morphs()`
        return $this->morphMany(Point::class, 'pointable');
    }
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function completedByUser()
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }

    /**
     * Get the city where the report occurred.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the district (district) where the report occurred.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the user who verified the report.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Get the user who completed the report.
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }

    /**
     * Get the media for the report.
     */
    public function media()
    {
        return $this->hasMany(ReportMedia::class);
    }

    /**
     * Get the comments for the report.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the votes for the report.
     */
    public function votes()
    {
        return $this->hasMany(ReportVote::class);
    }

    /**
     * Get the donations for the report.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the mission related to this report.
     */
    public function mission()
    {
        return $this->hasOne(Mission::class);
    }
}
