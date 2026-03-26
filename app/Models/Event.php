<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'event_date',
        'career_id',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
        ];
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function graduates(): BelongsToMany
    {
        return $this->belongsToMany(Graduate::class, 'event_graduate')
            ->withPivot('attended_at')
            ->withTimestamps();
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return "/storage/{$this->image_path}";
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->event_date->translatedFormat('d \d\e M, Y H:i');
    }

    public function getFormattedDateOnlyAttribute(): string
    {
        return $this->event_date->translatedFormat('d \d\e M, Y');
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->event_date->format('H:i');
    }
}
