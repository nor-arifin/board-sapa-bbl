<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $fillable = [
        'patient_identifier',
        'patient_id',
        'patient_ihs',
        'patient_medrec',
        'patient_medrec_local',
        'patient_title',
        'patient_name',
        'patient_birthdate',
        'patient_birthplace',
        'patient_gender',
        'patient_multipleBirth',
        'patient_phone',
        'patient_email',
        'patient_address_use',
        'patient_address_line',
        'patient_address_province',
        'patient_address_city',
        'patient_address_district',
        'patient_address_village',
        'patient_address_admcode',
        'patient_postcode',
        'patient_mother_name',
        'patient_origin',
        'patient_originId',
        'patient_active',
    ];

    protected function casts(): array
    {
        return [
            'patient_birthdate' => 'date',
            'patient_multipleBirth' => 'boolean',
            'patient_active' => 'boolean',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'patient_address_city', 'code');
    }

    /**
     * Scope untuk filter Kalimantan Selatan
     */
    public function scopeKalsel($query)
    {
        return $query->where('patient_address_province', '63');
    }
}
