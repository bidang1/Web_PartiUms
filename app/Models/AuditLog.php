<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    // We only use created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'field_changed',
        'old_value',
        'new_value',
        'created_at',
    ];

    /**
     * Boot the model.
     * Truncate action field before saving to prevent "Data too long" errors.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (isset($log->action) && mb_strlen($log->action) > 500) {
                $log->action = mb_substr($log->action, 0, 497) . '...';
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'entity_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include recent logs.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to filter by entity type and ID.
     */
    public function scopeForEntity(Builder $query, string $type, int $id): Builder
    {
        return $query->where('entity_type', $type)->where('entity_id', $id);
    }
}
