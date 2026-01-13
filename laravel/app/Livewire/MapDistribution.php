<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\City;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\Laboratory;
use Illuminate\Support\Facades\DB;

class MapDistribution extends Component
{
    public $selectedTestType = '';
    public $selectedRegion = '';
    public $selectedYear = '';
    
    public $testTypes = [];
    public $regions = [];
    public $years = [];

    public function mount()
    {
        $this->testTypes = $this->getTestTypes();
        $this->regions = $this->getRegions();
        $this->years = $this->getYears();
        $this->selectedYear = date('Y');
    }

    public function getTestTypes(): array
    {
        return [
            '' => 'Semua Jenis Skrining',
            'SHK' => 'SHK - Skrining Hipotiroid Kongenital',
            'S-HAK' => 'S-HAK - Skrining Hemoglobin Abnormal',
            'S-G6PD' => 'S-G6PD - Skrining Defisiensi G6PD',
        ];
    }

    public function getRegions(): array
    {
        $regions = ['' => 'Semua Wilayah'];
        
        try {
            $cities = City::kalsel()->orderBy('name')->get();
            foreach ($cities as $city) {
                $regions[$city->code] = $city->name;
            }
        } catch (\Exception $e) {
            // Fallback jika database belum siap
            $regions = array_merge($regions, [
                '6371' => 'KOTA BANJARMASIN',
                '6372' => 'KOTA BANJARBARU',
                '6303' => 'KABUPATEN BANJAR',
                '6304' => 'KABUPATEN BARITO KUALA',
                '6305' => 'KABUPATEN TAPIN',
                '6306' => 'KABUPATEN HULU SUNGAI SELATAN',
                '6307' => 'KABUPATEN HULU SUNGAI TENGAH',
                '6308' => 'KABUPATEN HULU SUNGAI UTARA',
                '6311' => 'KABUPATEN BALANGAN',
                '6309' => 'KABUPATEN TABALONG',
                '6301' => 'KABUPATEN TANAH LAUT',
                '6310' => 'KABUPATEN TANAH BUMBU',
                '6302' => 'KABUPATEN KOTABARU',
            ]);
        }
        
        return $regions;
    }

    public function getYears(): array
    {
        $currentYear = date('Y');
        $years = [];
        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
            $years[$i] = $i;
        }
        return $years;
    }

    public function getMarkersProperty(): array
    {
        try {
            $cities = City::kalsel()->get();
            
            return $cities->map(function ($city) {
                $screeningData = $this->getScreeningDataByCity($city->code);
                $coords = $city->coordinates;
                
                return [
                    'id' => $city->id,
                    'name' => $city->short_name,
                    'lat' => (float) $coords['lat'],
                    'lng' => (float) $coords['lng'],
                    'type' => str_contains($city->name, 'KOTA') ? 'Kota' : 'Kabupaten',
                    'address' => $city->name,
                    'region' => $city->name,
                    'region_code' => $city->code,
                    'screening' => $screeningData,
                ];
            })->when($this->selectedRegion, function ($collection) {
                return $collection->filter(fn ($item) => $item['region_code'] === $this->selectedRegion);
            })->values()->toArray();
        } catch (\Exception $e) {
            return $this->getDummyMarkers();
        }
    }

    protected function getScreeningDataByCity($cityCode): array
    {
        try {
            // Get patients from this city
            $patientIds = Patient::where('patient_address_city', $cityCode)
                ->pluck('patient_id');

            if ($patientIds->isEmpty()) {
                return [
                    'total_screened' => 0,
                    'total_positive' => 0,
                    'total_negative' => 0,
                ];
            }

            // Get visits for these patients
            $query = Visit::whereIn('visit_patientId', $patientIds);

            if ($this->selectedYear) {
                $query->whereYear('visit_date', $this->selectedYear);
            }

            $visitIds = $query->pluck('id');

            if ($visitIds->isEmpty()) {
                return [
                    'total_screened' => 0,
                    'total_positive' => 0,
                    'total_negative' => 0,
                ];
            }

            // Count laboratory tests
            $labQuery = Laboratory::whereIn('laboratory_visitId', $visitIds);

            if ($this->selectedTestType) {
                $labQuery->byTestType($this->selectedTestType);
            }

            $totalScreened = $labQuery->count();
            
            // Count positive results
            $positiveKeywords = ['positif', 'abnormal', 'tinggi', 'high', 'detected', 'reactive'];
            $totalPositive = (clone $labQuery)->where(function ($q) use ($positiveKeywords) {
                foreach ($positiveKeywords as $keyword) {
                    $q->orWhere('laboratory_result', 'like', "%{$keyword}%")
                      ->orWhere('laboratory_interpretation', 'like', "%{$keyword}%");
                }
            })->count();

            return [
                'total_screened' => $totalScreened,
                'total_positive' => $totalPositive,
                'total_negative' => $totalScreened - $totalPositive,
            ];
        } catch (\Exception $e) {
            return [
                'total_screened' => 0,
                'total_positive' => 0,
                'total_negative' => 0,
            ];
        }
    }

    protected function getDummyMarkers(): array
    {
        $allMarkers = [
            [
                'id' => 1,
                'name' => 'Banjarmasin',
                'lat' => -3.3194,
                'lng' => 114.5908,
                'type' => 'Kota',
                'address' => 'Ibukota Provinsi Kalimantan Selatan',
                'region_key' => '6371',
                'region_code' => '6371',
                'screening' => ['total_screened' => 3250, 'total_positive' => 38, 'total_negative' => 3212],
            ],
            [
                'id' => 2,
                'name' => 'Banjarbaru',
                'lat' => -3.4417,
                'lng' => 114.8333,
                'type' => 'Kota',
                'address' => 'Kota Banjarbaru',
                'region_key' => '6372',
                'region_code' => '6372',
                'screening' => ['total_screened' => 1680, 'total_positive' => 18, 'total_negative' => 1662],
            ],
            [
                'id' => 3,
                'name' => 'Banjar',
                'lat' => -3.4167,
                'lng' => 114.8500,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Martapura',
                'region_key' => '6303',
                'region_code' => '6303',
                'screening' => ['total_screened' => 2120, 'total_positive' => 24, 'total_negative' => 2096],
            ],
            [
                'id' => 4,
                'name' => 'Barito Kuala',
                'lat' => -3.0833,
                'lng' => 114.5833,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Marabahan',
                'region_key' => '6304',
                'region_code' => '6304',
                'screening' => ['total_screened' => 1450, 'total_positive' => 16, 'total_negative' => 1434],
            ],
            [
                'id' => 5,
                'name' => 'Tapin',
                'lat' => -2.9500,
                'lng' => 115.0167,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Rantau',
                'region_key' => '6305',
                'region_code' => '6305',
                'screening' => ['total_screened' => 980, 'total_positive' => 11, 'total_negative' => 969],
            ],
            [
                'id' => 6,
                'name' => 'Hulu Sungai Selatan',
                'lat' => -2.7667,
                'lng' => 115.1333,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Kandangan',
                'region_key' => '6306',
                'region_code' => '6306',
                'screening' => ['total_screened' => 1120, 'total_positive' => 13, 'total_negative' => 1107],
            ],
            [
                'id' => 7,
                'name' => 'Hulu Sungai Tengah',
                'lat' => -2.6000,
                'lng' => 115.2500,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Barabai',
                'region_key' => '6307',
                'region_code' => '6307',
                'screening' => ['total_screened' => 1280, 'total_positive' => 14, 'total_negative' => 1266],
            ],
            [
                'id' => 8,
                'name' => 'Hulu Sungai Utara',
                'lat' => -2.4167,
                'lng' => 115.2500,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Amuntai',
                'region_key' => '6308',
                'region_code' => '6308',
                'screening' => ['total_screened' => 1050, 'total_positive' => 12, 'total_negative' => 1038],
            ],
            [
                'id' => 9,
                'name' => 'Balangan',
                'lat' => -2.3000,
                'lng' => 115.5833,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Paringin',
                'region_key' => '6311',
                'region_code' => '6311',
                'screening' => ['total_screened' => 680, 'total_positive' => 8, 'total_negative' => 672],
            ],
            [
                'id' => 10,
                'name' => 'Tabalong',
                'lat' => -2.1667,
                'lng' => 115.4333,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Tanjung',
                'region_key' => '6309',
                'region_code' => '6309',
                'screening' => ['total_screened' => 1320, 'total_positive' => 15, 'total_negative' => 1305],
            ],
            [
                'id' => 11,
                'name' => 'Tanah Laut',
                'lat' => -3.7833,
                'lng' => 114.8333,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Pelaihari',
                'region_key' => '6301',
                'region_code' => '6301',
                'screening' => ['total_screened' => 1580, 'total_positive' => 17, 'total_negative' => 1563],
            ],
            [
                'id' => 12,
                'name' => 'Tanah Bumbu',
                'lat' => -3.4167,
                'lng' => 115.7000,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Batulicin',
                'region_key' => '6310',
                'region_code' => '6310',
                'screening' => ['total_screened' => 1450, 'total_positive' => 16, 'total_negative' => 1434],
            ],
            [
                'id' => 13,
                'name' => 'Kotabaru',
                'lat' => -3.2833,
                'lng' => 116.2167,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Kotabaru',
                'region_key' => '6302',
                'region_code' => '6302',
                'screening' => ['total_screened' => 1460, 'total_positive' => 16, 'total_negative' => 1444],
            ],
        ];

        // Filter by region if selected
        if ($this->selectedRegion) {
            $allMarkers = array_filter($allMarkers, function ($marker) {
                return $marker['region_code'] === $this->selectedRegion;
            });
        }

        return array_values($allMarkers);
    }

    public function updatedSelectedTestType()
    {
        $this->dispatch('markersUpdated', markers: $this->markers);
    }

    public function updatedSelectedRegion()
    {
        $this->dispatch('markersUpdated', markers: $this->markers);
    }

    public function updatedSelectedYear()
    {
        $this->dispatch('markersUpdated', markers: $this->markers);
    }

    public function resetFilters()
    {
        $this->selectedTestType = '';
        $this->selectedRegion = '';
        $this->selectedYear = date('Y');
        $this->dispatch('markersUpdated', markers: $this->markers);
    }

    public function render()
    {
        return view('livewire.map-distribution', [
            'markers' => $this->markers,
        ]);
    }
}
