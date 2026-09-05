<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'name',
        'slug',
        'tagline',
        'description',
        'date_start',
        'date_end',
        'pj_names',
        'htm_tiers',
        'gform_link',
        'gform_updated_by',
        'gform_updated_at',
        'status',
        'order',
        'is_deleted',
        'type',
        'location',
        'poster_path',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'date_start' => 'date',
            'date_end' => 'date',
            'pj_names' => 'array',
            'htm_tiers' => 'array',
            'gform_link' => 'array',
            'gform_updated_at' => 'datetime',
            'order' => 'integer',
            'is_deleted' => 'boolean',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug on create
        static::creating(function ($subEvent) {
            if (empty($subEvent->slug)) {
                $subEvent->slug = static::generateUniqueSlug($subEvent->name, $subEvent->year);
            }
        });

        // Delete poster file when the model is permanently deleted
        static::deleting(function ($subEvent) {
            if ($subEvent->poster_path && Storage::disk('public')->exists($subEvent->poster_path)) {
                Storage::disk('public')->delete($subEvent->poster_path);
            }
        });
    }

    /**
     * Helper to generate unique slug per year.
     */
    public static function generateUniqueSlug(string $name, int $year): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('year', $year)->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Scope a query to only include active/not deleted sub events.
     */
    public function scopeNotDeleted(Builder $query): Builder
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Scope a query to only include published sub events.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'PUBLISHED');
    }

    /**
     * Scope a query to only include sub events for a specific year.
     */
    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    /**
     * Get the documents for the sub event.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(SubEventDocument::class)->orderBy('order');
    }

    /**
     * Get the timeline items for the sub event.
     */
    public function timelineItems(): HasMany
    {
        return $this->hasMany(TimelineItem::class)->orderBy('order');
    }

    /**
     * Get the user who updated the Google Form link.
     */
    public function gformUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gform_updated_by');
    }

    /**
     * Accessor for registration button state.
     * Returns: 'open' (daftar), 'closed' (ditutup), 'coming_soon' (segera dibuka)
     */
    public function getRegistrationButtonStateAttribute(): string
    {
        if ($this->status === 'CLOSED') {
            return 'closed';
        }

        if ($this->status === 'PUBLISHED' && !empty($this->gform_link)) {
            return 'open';
        }

        return 'coming_soon';
    }

    /**
     * Accessor for full poster URL.
     */
    public function getPosterUrlAttribute(): ?string
    {
        if (!$this->poster_path) {
            return null;
        }

        // Jika poster_path merupakan URL eksternal (diawali http/https), gunakan langsung
        if (str_starts_with($this->poster_path, 'http://') || str_starts_with($this->poster_path, 'https://')) {
            return $this->poster_path;
        }

        return asset('storage/' . $this->poster_path);
    }
}
