<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissionCommunity extends Model
{
    use HasFactory;

    protected $table = 'mission_communities';
    protected $guarded = [
       'id'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'awarded_at' => 'datetime',
    ];

    public function mission(): BelongsTo
    {
        return $this->belongsTo(Mission::class);
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }
}
