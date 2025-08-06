<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissionVolunteer extends Model
{
    use HasFactory;

    protected $table = 'mission_volunteers';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'is_leader' => 'boolean',
        'awarded_at' => 'datetime',
    ];

    public function points()
    {
        return $this->morphMany(Point::class, 'pointable');
    }

    public function mission(): BelongsTo
    {
        return $this->belongsTo(Mission::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
