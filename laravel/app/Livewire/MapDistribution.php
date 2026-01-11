<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Facility;
use App\Models\Region;
use App\Models\Screening;

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
        $this->testTypes = Screening::testTypes();
        $this->regions = $this->getRegions();
        $this->years = $this->getYears();
        $this->selectedYear = date('Y');
    }

    public function getRegions(): array
    {
        return [
            '' => 'Semua Wilayah',
            'banjarmasin' => 'Kota Banjarmasin',
            'banjarbaru' => 'Kota Banjarbaru',
            'banjar' => 'Kabupaten Banjar',
            'baritokuala' => 'Kabupaten Barito Kuala',
            'tapin' => 'Kabupaten Tapin',
            'hss' => 'Kabupaten Hulu Sungai Selatan',
            'hst' => 'Kabupaten Hulu Sungai Tengah',
            'hsu' => 'Kabupaten Hulu Sungai Utara',
            'balangan' => 'Kabupaten Balangan',
            'tabalong' => 'Kabupaten Tabalong',
            'tanahlaut' => 'Kabupaten Tanah Laut',
            'tanahbumbu' => 'Kabupaten Tanah Bumbu',
            'kotabaru' => 'Kabupaten Kotabaru',
        ];
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
            $query = Facility::active()->withCoordinates()->with('region');

            if ($this->selectedRegion) {
                $query->where('region_id', $this->selectedRegion);
            }

            $facilities = $query->get();

            return $facilities->map(function ($facility) {
                $screeningData = $this->getScreeningData($facility->id);
                
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'lat' => (float) $facility->latitude,
                    'lng' => (float) $facility->longitude,
                    'type' => $facility->type,
                    'address' => $facility->address,
                    'region' => $facility->region?->name ?? '-',
                    'screening' => $screeningData,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return $this->getDummyMarkers();
        }
    }

    protected function getScreeningData($facilityId): array
    {
        try {
            $query = Screening::where('facility_id', $facilityId);

            if ($this->selectedTestType) {
                $query->byTestType($this->selectedTestType);
            }

            if ($this->selectedYear) {
                $query->byYear($this->selectedYear);
            }

            $data = $query->selectRaw('
                COALESCE(SUM(total_screened), 0) as total_screened,
                COALESCE(SUM(total_positive), 0) as total_positive,
                COALESCE(SUM(total_negative), 0) as total_negative
            ')->first();

            return [
                'total_screened' => $data->total_screened ?? 0,
                'total_positive' => $data->total_positive ?? 0,
                'total_negative' => $data->total_negative ?? 0,
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
                'name' => 'Kota Banjarmasin',
                'lat' => -3.3194,
                'lng' => 114.5908,
                'type' => 'Kota',
                'address' => 'Ibukota Provinsi Kalimantan Selatan',
                'region_key' => 'banjarmasin',
                'screening' => ['total_screened' => 3250, 'total_positive' => 38, 'total_negative' => 3212],
            ],
            [
                'id' => 2,
                'name' => 'Kota Banjarbaru',
                'lat' => -3.4417,
                'lng' => 114.8333,
                'type' => 'Kota',
                'address' => 'Kota Banjarbaru',
                'region_key' => 'banjarbaru',
                'screening' => ['total_screened' => 1680, 'total_positive' => 18, 'total_negative' => 1662],
            ],
            [
                'id' => 3,
                'name' => 'Kabupaten Banjar',
                'lat' => -3.4167,
                'lng' => 114.8500,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Martapura',
                'region_key' => 'banjar',
                'screening' => ['total_screened' => 2120, 'total_positive' => 24, 'total_negative' => 2096],
            ],
            [
                'id' => 4,
                'name' => 'Kabupaten Barito Kuala',
                'lat' => -3.0833,
                'lng' => 114.5833,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Marabahan',
                'region_key' => 'baritokuala',
                'screening' => ['total_screened' => 1450, 'total_positive' => 16, 'total_negative' => 1434],
            ],
            [
                'id' => 5,
                'name' => 'Kabupaten Tapin',
                'lat' => -2.9500,
                'lng' => 115.0167,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Rantau',
                'region_key' => 'tapin',
                'screening' => ['total_screened' => 980, 'total_positive' => 11, 'total_negative' => 969],
            ],
            [
                'id' => 6,
                'name' => 'Kabupaten Hulu Sungai Selatan',
                'lat' => -2.7667,
                'lng' => 115.1333,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Kandangan',
                'region_key' => 'hss',
                'screening' => ['total_screened' => 1120, 'total_positive' => 13, 'total_negative' => 1107],
            ],
            [
                'id' => 7,
                'name' => 'Kabupaten Hulu Sungai Tengah',
                'lat' => -2.6000,
                'lng' => 115.2500,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Barabai',
                'region_key' => 'hst',
                'screening' => ['total_screened' => 1280, 'total_positive' => 14, 'total_negative' => 1266],
            ],
            [
                'id' => 8,
                'name' => 'Kabupaten Hulu Sungai Utara',
                'lat' => -2.4167,
                'lng' => 115.2500,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Amuntai',
                'region_key' => 'hsu',
                'screening' => ['total_screened' => 1050, 'total_positive' => 12, 'total_negative' => 1038],
            ],
            [
                'id' => 9,
                'name' => 'Kabupaten Balangan',
                'lat' => -2.3000,
                'lng' => 115.5833,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Paringin',
                'region_key' => 'balangan',
                'screening' => ['total_screened' => 680, 'total_positive' => 8, 'total_negative' => 672],
            ],
            [
                'id' => 10,
                'name' => 'Kabupaten Tabalong',
                'lat' => -2.1667,
                'lng' => 115.4333,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Tanjung',
                'region_key' => 'tabalong',
                'screening' => ['total_screened' => 1320, 'total_positive' => 15, 'total_negative' => 1305],
            ],
            [
                'id' => 11,
                'name' => 'Kabupaten Tanah Laut',
                'lat' => -3.7833,
                'lng' => 114.8333,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Pelaihari',
                'region_key' => 'tanahlaut',
                'screening' => ['total_screened' => 1580, 'total_positive' => 17, 'total_negative' => 1563],
            ],
            [
                'id' => 12,
                'name' => 'Kabupaten Tanah Bumbu',
                'lat' => -3.4167,
                'lng' => 115.7000,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Batulicin',
                'region_key' => 'tanahbumbu',
                'screening' => ['total_screened' => 1450, 'total_positive' => 16, 'total_negative' => 1434],
            ],
            [
                'id' => 13,
                'name' => 'Kabupaten Kotabaru',
                'lat' => -3.2833,
                'lng' => 116.2167,
                'type' => 'Kabupaten',
                'address' => 'Ibukota: Kotabaru',
                'region_key' => 'kotabaru',
                'screening' => ['total_screened' => 1460, 'total_positive' => 16, 'total_negative' => 1444],
            ],
        ];

        // Filter by region if selected
        if ($this->selectedRegion) {
            $allMarkers = array_filter($allMarkers, function ($marker) {
                return $marker['region_key'] === $this->selectedRegion;
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
