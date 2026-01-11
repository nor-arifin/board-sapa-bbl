<div>
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Jenis Tes Filter -->
            <div>
                <label for="testType" class="block text-sm font-medium text-gray-700 mb-2">Jenis Tes</label>
                <select 
                    wire:model.live="selectedTestType" 
                    id="testType"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                >
                    <option value="">Semua Jenis Tes</option>
                    @foreach($testTypes as $key => $label)
                        <option value="{{ $key }}">{{ $key }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Wilayah Filter -->
            <div>
                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Wilayah</label>
                <select 
                    wire:model.live="selectedRegion" 
                    id="region"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                >
                    @foreach($regions as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Tahun Filter -->
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select 
                    wire:model.live="selectedYear" 
                    id="year"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                >
                    @foreach($years as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Reset Button -->
            <div class="flex items-end">
                <button 
                    wire:click="resetFilters"
                    class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Filter
                </button>
            </div>
        </div>
    </div>
    
    <!-- Map Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div 
            id="map" 
            class="w-full h-125 md:h-150"
            wire:ignore
        ></div>
    </div>
    
    <!-- Legend -->
    <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
        <h4 class="font-semibold text-gray-900 mb-4">Keterangan</h4>
        <div class="flex flex-wrap gap-6">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-primary border-2 border-primary-dark"></div>
                <span class="text-sm text-gray-600">Kota</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-accent border-2 border-yellow-500"></div>
                <span class="text-sm text-gray-600">Kabupaten (Capaian Tinggi &gt;1000)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded-full bg-primary-dark border-2 border-teal-800"></div>
                <span class="text-sm text-gray-600">Kabupaten</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        const map = L.map('map').setView([-3.3, 115.5], 8);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Custom icon
        const createIcon = (color) => {
            return L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid #006962; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12],
                popupAnchor: [0, -12]
            });
        };
        
        // Markers layer group
        let markersLayer = L.layerGroup().addTo(map);
        
        // Function to update markers
        const updateMarkers = (markers) => {
            markersLayer.clearLayers();
            
            markers.forEach(marker => {
                let color;
                if (marker.type === 'Kota') {
                    color = '#00b4ab'; // Primary untuk Kota
                } else if (marker.screening.total_screened > 1000) {
                    color = '#d0dd27'; // Accent untuk capaian tinggi
                } else {
                    color = '#006962'; // Primary dark untuk Kabupaten
                }
                const icon = createIcon(color);
                
                const popupContent = `
                    <div class="p-3 min-w-[200px]">
                        <h3 class="font-bold text-gray-900 mb-2">${marker.name}</h3>
                        <p class="text-sm text-gray-500 mb-3">${marker.address || '-'}</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipe:</span>
                                <span class="font-medium">${marker.type}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Skrining:</span>
                                <span class="font-medium text-primary">${marker.screening.total_screened.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    </div>
                `;
                
                L.marker([marker.lat, marker.lng], { icon })
                    .bindPopup(popupContent)
                    .addTo(markersLayer);
            });
        };
        
        // Initial markers
        const initialMarkers = @json($markers);
        updateMarkers(initialMarkers);
        
        // Listen for Livewire updates
        Livewire.on('markersUpdated', (event) => {
            updateMarkers(event.markers);
        });
    });
</script>
@endpush
