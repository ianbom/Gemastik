<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportVote extends Model
{
    use HasFactory;

    protected $table = 'report_votes';
    protected $fillable = ['user_id', 'vote_type', 'report_id'];

    protected $guarded = [
        'id'
    ];

    /**
     * Get the user who made the vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the report that the vote belongs to.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
