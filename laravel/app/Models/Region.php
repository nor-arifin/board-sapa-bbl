<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $table = 'regions';
    
    protected $fillable = [
        'name',
        'code',
        'type',
        'parent_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

    public function scopeProvinces($query)
    {
        return $query->where('type', 'province');
    }

    public function scopeDistricts($query)
    {
        return $query->where('type', 'district');
    }
}
