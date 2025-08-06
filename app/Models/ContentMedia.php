<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentMedia extends Model
{
    use HasFactory;

    protected $table = 'content_media';

    protected $guarded = [
        'id'
    ];

    /**
     * Get the content that owns the media.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
