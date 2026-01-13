<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visit extends Model
{
    protected $fillable = [
        'visit_id',
        'visit_encode',
        'visit_labId',
        'visit_date',
        'visit_type',
        'visit_patientIhs',
        'visit_patientId',
        'visit_patientName',
        'visit_medrec',
        'visit_status',
        'visit_start',
        'visit_end',
        'visit_class',
        'visit_location',
        'visit_doctorId',
        'visit_doctorName',
        'visit_encounterId',
        'visit_encounterType',
        'visit_diagnosisCode',
        'visit_diagnosisName',
        'visit_originId',
        'visit_originName',
        'visit_notes',
        'visit_expertise',
        'visit_referer',
        'visit_destination',
        'visit_registrar',
        'visit_sendDatetime',
        'visit_deliveredDatetime',
        'visit_trackingId',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'visit_start' => 'datetime',
            'visit_end' => 'datetime',
            'visit_sendDatetime' => 'datetime',
            'visit_deliveredDatetime' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'visit_patientId', 'patient_id');
    }

    public function laboratories(): HasMany
    {
        return $this->hasMany(Laboratory::class, 'laboratory_visitId', 'id');
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('visit_date', $year);
    }

    /**
     * Scope untuk filter berdasarkan jenis skrining (SHK, S-HAK, S-G6PD)
     */
    public function scopeByTestType($query, $testType)
    {
        return $query->whereHas('laboratories', function ($q) use ($testType) {
            $q->where('laboratory_test_name', 'like', "%{$testType}%");
        });
    }
}
