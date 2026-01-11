<div>
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Total Skrining -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-primary">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-primary bg-primary/10 px-2 py-1 rounded-full">Tahun {{ $selectedYear }}</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($kpiData['total_screened'], 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-500">Total Bayi Diskrining</p>
        </div>
        
        <!-- Kasus Positif -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-red-500 bg-red-50 px-2 py-1 rounded-full">{{ $kpiData['detection_rate'] }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($kpiData['total_positive'], 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-500">Kasus Terdeteksi</p>
        </div>
        
        <!-- Fasilitas -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-accent">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-primary-dark bg-accent/20 px-2 py-1 rounded-full">Aktif</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($kpiData['facility_count'], 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-500">Fasilitas Kesehatan</p>
        </div>
        
        <!-- Wilayah -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-primary-dark">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-dark/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium text-primary-dark bg-primary-dark/10 px-2 py-1 rounded-full">Kab/Kota</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($kpiData['region_count'], 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-500">Kabupaten/Kota Tercakup</p>
        </div>
    </div>
    
    <!-- Summary Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Capaian per Jenis Skrining</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Skrining</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Skrining</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tingkat Deteksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tableData as $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded bg-primary/10 text-primary-dark mr-2">{{ $row['test_type'] }}</span>
                                    <span class="text-sm text-gray-600">{{ Str::limit($row['test_name'], 40) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">{{ number_format($row['total_screened'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full" style="width: {{ min($row['detection_rate'] * 10, 100) }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600">{{ $row['detection_rate'] }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p>Belum ada data skrining untuk periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($tableData) > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-6 py-4 font-semibold text-gray-900">Total Keseluruhan</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">{{ number_format($kpiData['total_screened'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-primary">{{ $kpiData['detection_rate'] }}%</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
