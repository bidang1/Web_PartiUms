<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'sub_event_id',
        'date',
        'title',
        'description',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'date' => 'date',
            'order' => 'integer',
        ];
    }

    /**
     * Get the sub event associated with this timeline item (nullable).
     */
    public function subEvent(): BelongsTo
    {
        return $this->belongsTo(SubEvent::class);
    }

    /**
     * Scope a query to only include timeline items for a specific year.
     */
    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }
}
