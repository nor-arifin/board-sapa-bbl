<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Screening extends Model
{
    protected $table = 'screenings';
    
    protected $fillable = [
        'facility_id',
        'region_id',
        'test_type',
        'screening_date',
        'total_screened',
        'total_positive',
        'total_negative',
        'year',
        'month',
    ];

    protected $casts = [
        'screening_date' => 'date',
        'total_screened' => 'integer',
        'total_positive' => 'integer',
        'total_negative' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
    ];

    const TEST_TYPE_SHK = 'SHK';
    const TEST_TYPE_SHAK = 'S-HAK';
    const TEST_TYPE_SG6PD = 'S-G6PD';

    public static function testTypes(): array
    {
        return [
            self::TEST_TYPE_SHK => 'Skrining Hipotiroid Kongenital (SHK)',
            self::TEST_TYPE_SHAK => 'Skrining Hemoglobin Abnormal Kongenital (S-HAK)',
            self::TEST_TYPE_SG6PD => 'Skrining Glucose-6-Phosphate Dehydrogenase (S-G6PD)',
        ];
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function scopeByTestType($query, $testType)
    {
        return $query->where('test_type', $testType);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId);
    }
}
