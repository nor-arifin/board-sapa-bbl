<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $table = 'indonesia_cities';

    protected $fillable = [
        'code',
        'province_code',
        'name',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'city_code', 'code');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'patient_address_city', 'code');
    }

    /**
     * Scope untuk filter Kalimantan Selatan (province_code = 63)
     */
    public function scopeKalsel($query)
    {
        return $query->where('province_code', '63');
    }

    /**
     * Get koordinat default untuk setiap kota/kabupaten di Kalimantan Selatan
     */
    public function getCoordinatesAttribute(): array
    {
        $coordinates = [
            '6301' => ['lat' => -3.7833, 'lng' => 114.8333], // Tanah Laut
            '6302' => ['lat' => -3.2833, 'lng' => 116.2167], // Kotabaru
            '6303' => ['lat' => -3.4167, 'lng' => 114.8500], // Banjar
            '6304' => ['lat' => -3.0833, 'lng' => 114.5833], // Barito Kuala
            '6305' => ['lat' => -2.9500, 'lng' => 115.0167], // Tapin
            '6306' => ['lat' => -2.7667, 'lng' => 115.1333], // Hulu Sungai Selatan
            '6307' => ['lat' => -2.6000, 'lng' => 115.2500], // Hulu Sungai Tengah
            '6308' => ['lat' => -2.4167, 'lng' => 115.2500], // Hulu Sungai Utara
            '6309' => ['lat' => -2.1667, 'lng' => 115.4333], // Tabalong
            '6310' => ['lat' => -3.4167, 'lng' => 115.7000], // Tanah Bumbu
            '6311' => ['lat' => -2.3000, 'lng' => 115.5833], // Balangan
            '6371' => ['lat' => -3.3194, 'lng' => 114.5908], // Banjarmasin
            '6372' => ['lat' => -3.4417, 'lng' => 114.8333], // Banjarbaru
        ];

        return $coordinates[$this->code] ?? ['lat' => -3.3194, 'lng' => 114.5908];
    }

    /**
     * Get nama singkat kabupaten/kota
     */
    public function getShortNameAttribute(): string
    {
        return str_replace(['KABUPATEN ', 'KOTA '], '', $this->name);
    }
}
