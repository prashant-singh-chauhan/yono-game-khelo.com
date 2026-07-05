<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating'      => 'integer',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
