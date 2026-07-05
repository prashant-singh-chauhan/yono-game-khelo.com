<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class App extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_new_release' => 'boolean',
        'sign_up_bonus'  => 'integer',
        'min_withdrawal' => 'integer',
        'rating'         => 'decimal:1',
    ];

    protected static function booted(): void
    {
        static::saving(function (App $app) {
            if (blank($app->slug)) {
                $app->slug = Str::slug($app->name);
            } else {
                $app->slug = Str::slug($app->slug);
            }
        });
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Resolve the best available logo source for the app.
     */
    public function logo(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/'.$this->logo_path);
        }

        return $this->logo_url ?: null;
    }

    /**
     * Two-letter fallback initials for the dynamic avatar.
     */
    public function initials(): string
    {
        return Str::upper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $this->name), 0, 2));
    }

    public function featuresArray(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->features))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->values()
            ->all();
    }

    public function stepsArray(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->download_steps))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->values()
            ->all();
    }
}
