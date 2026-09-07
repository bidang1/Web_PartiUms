<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model Sponsor
 *
 * Mengelola data mitra dan sponsor event PARTI berdasarkan tahun pelaksanaan.
 * Menyimpan informasi tier kemitraan, urutan tampilan, status keaktifan,
 * serta memberikan akses URL publik ke file logo sponsor.
 */
class Sponsor extends Model
{
    use HasFactory;

    /**
     * Konstanta tingkat (tier) kemitraan sponsor.
     */
    public const TIER_PLATINUM = 'PLATINUM';
    public const TIER_GOLD = 'GOLD';
    public const TIER_SILVER = 'SILVER';
    public const TIER_BRONZE = 'BRONZE';

    /**
     * Daftar seluruh tier sponsor yang valid.
     */
    public const TIERS = [
        self::TIER_PLATINUM,
        self::TIER_GOLD,
        self::TIER_SILVER,
        self::TIER_BRONZE,
    ];

    protected $fillable = [
        'year',
        'name',
        'logo_path',
        'website_url',
        'tier',
        'order',
        'is_active',
    ];

    /**
     * Tipe data atribut model yang di-cast otomatis oleh Eloquent.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Listener lifecycle Eloquent saat model dijalankan.
     *
     * Callback 'deleting' memastikan file fisik logo di disk publik otomatis terhapus
     * saat data sponsor dihapus, sehingga mencegah berkas tak terpakai menumpuk di server.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($sponsor) {
            if ($sponsor->logo_path && Storage::disk('public')->exists($sponsor->logo_path)) {
                Storage::disk('public')->delete($sponsor->logo_path);
            }
        });
    }

    /**
     * Filter query untuk menyaring sponsor yang berstatus aktif (ditampilkan publik).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter query untuk menyaring sponsor berdasarkan tahun pelaksanaan event PARTI.
     */
    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    /**
     * Accessor untuk mendapatkan URL publik lengkap file logo sponsor.
     *
     * Mendukung tautan gambar eksternal (http/https) maupun lokasi berkas dalam penyimpanan publik lokal.
     * Mengembalikan null apabila path logo tidak terisi.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo_path)) {
            return null;
        }

        if (str_starts_with($this->logo_path, 'http://') || str_starts_with($this->logo_path, 'https://')) {
            return $this->logo_path;
        }

        $cleanPath = ltrim($this->logo_path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        return asset('storage/' . $cleanPath);
    }
}

