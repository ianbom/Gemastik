<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    // protected $casts = [
    //     'payment_response' => 'array',
    // ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the report that the donation belongs to.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
