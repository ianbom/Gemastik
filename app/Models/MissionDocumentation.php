<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissionDocumentation extends Model
{
    use HasFactory;

    protected $table = 'mission_documentation';

    protected $guarded = [
        'id'
    ];

    /**
     * Get the mission that owns the documentation.
     */
    public function mission(): BelongsTo
    {
        return $this->belongsTo(Mission::class);
    }

    /**
     * Get the user who uploaded the documentation.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_user_id');
    }
}
