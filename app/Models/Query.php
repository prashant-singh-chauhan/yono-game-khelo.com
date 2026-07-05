<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_read'     => 'boolean',
        'received_at' => 'datetime',
    ];

    public function excerpt(int $words = 12): string
    {
        return \Illuminate\Support\Str::words(strip_tags((string) $this->message), $words, '…');
    }

    public function attachmentUrl(): ?string
    {
        if (blank($this->attachment)) {
            return null;
        }

        // Absolute URLs stored as-is; local storage paths resolved to /storage/…
        return \Illuminate\Support\Str::startsWith($this->attachment, ['http://', 'https://'])
            ? $this->attachment
            : asset('storage/'.$this->attachment);
    }

    public function attachmentName(): ?string
    {
        return $this->attachment ? basename($this->attachment) : null;
    }
}
