<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laboratory extends Model
{
    protected $table = 'laboratory';
    
    protected $fillable = [
        'laboratory_id',
        'laboratory_visitId',
        'laboratory_patientMedrec',
        'laboratory_code',
        'laboratory_loinc',
        'laboratory_test_name',
        'laboratory_result',
        'laboratory_reference',
        'laboratory_interpretation',
        'laboratory_expertise',
        'laboratory_start',
        'laboratory_end',
        'laboratory_status',
        'laboratory_handlerId',
        'laboratory_verifiedId',
        'laboratory_datetimeVerified',
        'laboratory_validatedId',
        'laboratory_datetimeValidated',
        'laboratory_notes',
        'laboratory_encode',
    ];

    protected function casts(): array
    {
        return [
            'laboratory_start' => 'datetime',
            'laboratory_end' => 'datetime',
            'laboratory_datetimeVerified' => 'datetime',
            'laboratory_datetimeValidated' => 'datetime',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class, 'laboratory_visitId');
    }

    /**
     * Scope untuk filter berdasarkan jenis skrining
     */
    public function scopeByTestType($query, $testType)
    {
        return $query->where('laboratory_test_name', 'like', "%{$testType}%");
    }

    /**
     * Scope untuk skrining SHK (Hipotiroid Kongenital)
     */
    public function scopeShk($query)
    {
        return $query->where('laboratory_test_name', 'like', '%SHK%')
                    ->orWhere('laboratory_test_name', 'like', '%TSH%')
                    ->orWhere('laboratory_test_name', 'like', '%Hipotiroid%');
    }

    /**
     * Scope untuk skrining S-HAK (Hemoglobin Abnormal)
     */
    public function scopeShak($query)
    {
        return $query->where('laboratory_test_name', 'like', '%S-HAK%')
                    ->orWhere('laboratory_test_name', 'like', '%Hemoglobin%')
                    ->orWhere('laboratory_test_name', 'like', '%Thalassemia%');
    }

    /**
     * Scope untuk skrining S-G6PD
     */
    public function scopeSg6pd($query)
    {
        return $query->where('laboratory_test_name', 'like', '%G6PD%');
    }

    /**
     * Check apakah hasil positif/abnormal
     */
    public function isPositive(): bool
    {
        $positiveKeywords = ['positif', 'abnormal', 'tinggi', 'high', 'detected', 'reactive'];
        $result = strtolower($this->laboratory_result ?? '');
        $interpretation = strtolower($this->laboratory_interpretation ?? '');
        
        foreach ($positiveKeywords as $keyword) {
            if (str_contains($result, $keyword) || str_contains($interpretation, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
}
