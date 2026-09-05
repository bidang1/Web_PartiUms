<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SubEventDocument extends Model
{
    use HasFactory;

    // Turn off default timestamps, we use custom uploaded_at
    public $timestamps = false;

    protected $fillable = [
        'sub_event_id',
        'label',
        'file_path',
        'file_type',
        'file_size_bytes',
        'order',
        'uploaded_by',
        'uploaded_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'sub_event_id' => 'integer',
            'file_size_bytes' => 'integer',
            'order' => 'integer',
            'uploaded_by' => 'integer',
            'uploaded_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Delete actual document file from disk when the model is deleted
        static::deleting(function ($document) {
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
        });
    }

    /**
     * Get the sub event that owns this document.
     */
    public function subEvent(): BelongsTo
    {
        return $this->belongsTo(SubEvent::class);
    }

    /**
     * Get the user who uploaded this document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Accessor for full download URL.
     */
    public function getDownloadUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Accessor for human-readable file size.
     */
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size_bytes;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }
}
