<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $guarded = ['id'];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the report that the comment belongs to.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the parent comment if this is a reply.
     */
    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'reply_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'reply_id');
    }

    public function getFullMediaUrlAttribute(): ?string
    {
        if (!$this->media_url) {
            return null;
        }

        // If already a full URL, return as is
        if (str_starts_with($this->media_url, 'http')) {
            return $this->media_url;
        }

        // Otherwise, prepend the app URL
        return url($this->media_url);
    }

    /**
     * Check if the comment has media.
     */
    public function hasMedia(): bool
    {
        return !empty($this->media_url);
    }

    /**
     * Check if the comment is a reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->reply_id);
    }

    /**
     * Check if the comment is a parent comment.
     */
    public function isParent(): bool
    {
        return is_null($this->reply_id);
    }

    /**
     * Get the comment hierarchy (parent with all replies).
     */
    public function getHierarchy()
    {
        if ($this->isReply()) {
            return $this->parent()->with('replies')->first();
        }

        return $this->load('replies');
    }
}
