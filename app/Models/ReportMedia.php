<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportMedia extends Model
{
    use HasFactory;

    protected $table = 'report_media'; // Pastikan nama tabel sesuai jika berbeda dari konvensi

    protected $guarded = [
        'id'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    /**
     * Check if media is a video
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    /**
     * Get full URL for media
     */
    public function getFullUrlAttribute(): string
    {
        if (filter_var($this->media_url, FILTER_VALIDATE_URL)) {
            return $this->media_url;
        }

        return asset('storage/' . $this->media_url);
    }
}
