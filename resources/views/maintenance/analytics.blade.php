@extends('layouts.app')

@section('title', 'Analitik & Grafik')

@section('content')
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Analitik & Grafik</h1>
            <p class="mt-2 text-gray-600">Analisis data kerusakan mesin untuk pengambilan keputusan.</p>
        </div>
        <div>
            <form id="exportForm" action="{{ route('maintenance.analytics.export.pdf') }}" method="GET" class="inline">
                <input type="hidden" name="date_from" id="export_date_from">
                <input type="hidden" name="date_to" id="export_date_to">
                <input type="hidden" name="year" id="export_year">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Laporan</dt>
                            <dd class="text-lg font-medium text-gray-900 stat-total-reports">{{ $stats['total_reports'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Rata-rata Waktu Perbaikan</dt>
                            <dd class="text-lg font-medium text-gray-900 stat-avg-repair-time">
                                @if($stats['avg_repair_time'])
                                    {{ round($stats['avg_repair_time']) }} menit
                                @else
                                    N/A
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Mesin Terbanyak Kerusakan</dt>
                            <dd class="text-lg font-medium text-gray-900 stat-top-machine">{{ $stats['top_machine'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Jenis Kerusakan Terbanyak</dt>
                            <dd class="text-lg font-medium text-gray-900 stat-top-event-type">{{ $stats['top_event_type'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Part Terbanyak Digunakan</dt>
                            <dd class="text-lg font-medium text-gray-900 stat-top-part">{{ $stats['top_part'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Analitik</h3>
            <form id="analyticsFilter" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Date Range -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Tanggal Dari</label>
                    <input type="date" id="date_from" name="date_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                    <input type="date" id="date_to" name="date_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Tahun (Opsional)</label>
                    <select id="year" name="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Tahun</option>
                        @php
                            $currentYear = date('Y');
                            for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                                echo "<option value='$i'>$i</option>";
                            }
                        @endphp
                    </select>
                </div>
                <div class="md:col-span-3 flex items-end">
                    <button type="button" id="applyFilterBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Terapkan Filter
                    </button>
                    <button type="button" id="resetFilterBtn" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Breakdown Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Laporan Kerusakan Bulanan</h3>
                    <div class="text-sm text-gray-500" id="chartPeriod"></div>
                </div>
                <div class="h-64">
                    <canvas id="monthlyBreakdownChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Machine Breakdown Frequency -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Frekuensi Kerusakan Mesin</h3>
                <div class="h-64">
                    <canvas id="machineBreakdownChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Event Type Frequency -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Jenis Kerusakan Terbanyak</h3>
                <div class="h-64">
                    <canvas id="eventTypeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Part Usage Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Penggunaan Part Terbanyak</h3>
                <div class="h-64">
                    <canvas id="partUsageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Machines Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mesin dengan Kerusakan Terbanyak</h3>
                <div class="overflow-x-auto">
                    <table id="machineTable" class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kerusakan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Downtime</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($machineBreakdowns as $machine)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $machine->machine->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $machine->breakdown_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($machine->total_downtime_minutes)
                                            @php
                                                $hours = round($machine->total_downtime_minutes / 60, 1);
                                            @endphp
                                            {{ $hours }} jam
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Event Type Frequency Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Jenis Kerusakan Terbanyak</h3>
                <div class="overflow-x-auto">
                    <table id="eventTypeTable" class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kerusakan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frekuensi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($eventTypeFrequency as $eventType)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $eventType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $eventType->frequency }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Part Usage Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Penggunaan Part Terbanyak</h3>
                <div class="overflow-x-auto">
                    <table id="partUsageTable" class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Part</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kuantitas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Penggunaan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($partUsage as $part)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $part->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $part->total_quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $part->usage_count }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Breakdown Chart
    const monthlyCtx = document.getElementById('monthlyBreakdownChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthlyStats as $stat)
                    '{{ date("M Y", mktime(0, 0, 0, $stat->month, 1, $stat->year)) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Total Laporan',
                data: [
                    @foreach($monthlyStats as $stat)
                        {{ $stat->total_reports }},
                    @endforeach
                ],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Machine Breakdown Chart - Modified colors
    const machineCtx = document.getElementById('machineBreakdownChart').getContext('2d');
    const machineChart = new Chart(machineCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($machineBreakdowns as $machine)
                    '{{ $machine->machine->name }}',
                @endforeach
            ],
            datasets: [{
                label: 'Jumlah Kerusakan',
                data: [
                    @foreach($machineBreakdowns as $machine)
                        {{ $machine->breakdown_count }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',   // Blue
                    'rgba(239, 68, 68, 0.8)',    // Red
                    'rgba(16, 185, 129, 0.8)',   // Green
                    'rgba(245, 158, 11, 0.8)',   // Yellow
                    'rgba(139, 92, 246, 0.8)',   // Purple
                    'rgba(236, 72, 153, 0.8)',   // Pink
                    'rgba(6, 182, 212, 0.8)',    // Cyan
                    'rgba(132, 204, 22, 0.8)',   // Lime
                    'rgba(249, 115, 22, 0.8)',   // Orange
                    'rgba(99, 102, 241, 0.8)'    // Indigo
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(239, 68, 68)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(139, 92, 246)',
                    'rgb(236, 72, 153)',
                    'rgb(6, 182, 212)',
                    'rgb(132, 204, 22)',
                    'rgb(249, 115, 22)',
                    'rgb(99, 102, 241)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Kerusakan'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Mesin'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Event Type Chart
    const eventCtx = document.getElementById('eventTypeChart').getContext('2d');
    const eventChart = new Chart(eventCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($eventTypeFrequency as $eventType)
                    '{{ $eventType->name }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($eventTypeFrequency as $eventType)
                        {{ $eventType->frequency }},
                    @endforeach
                ],
                backgroundColor: [
                    '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
                    '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Part Usage Chart
    const partCtx = document.getElementById('partUsageChart').getContext('2d');
    const partChart = new Chart(partCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($partUsage as $part)
                    '{{ $part->name }}',
                @endforeach
            ],
            datasets: [{
                label: 'Total Kuantitas',
                data: [
                    @foreach($partUsage as $part)
                        {{ $part->total_quantity }},
                    @endforeach
                ],
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgb(239, 68, 68)',
                borderWidth: 1
            }, {
                label: 'Jumlah Penggunaan',
                data: [
                    @foreach($partUsage as $part)
                        {{ $part->usage_count }},
                    @endforeach
                ],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Nama Part'
                    }
                }
            }
        }
    });


        // Filter Functions
        function applyFilters() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            const year = document.getElementById('year').value;
            
            console.log('Applying filters:', { dateFrom, dateTo, year });
            
            // Build query parameters
            const params = new URLSearchParams();
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            if (year) params.append('year', year);
            
            // Show loading state
            const applyBtn = document.getElementById('applyFilterBtn');
            const originalText = applyBtn.innerHTML;
            applyBtn.innerHTML = '<span class="animate-spin mr-2">⟳</span> Memproses...';
            applyBtn.disabled = true;
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            console.log('Fetching from:', `/maintenance/analytics/filter?${params.toString()}`);
            
            // Fetch filtered data with credentials
            fetch(`/maintenance/analytics/filter?${params.toString()}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    console.log('Monthly stats:', data.monthlyStats);
                    console.log('Machine breakdowns:', data.machineBreakdowns);
                    console.log('Event type frequency:', data.eventTypeFrequency);
                    // Update charts with filtered data
                    updateCharts(data);
                    
                    // Update chart period display
                    updateChartPeriod(dateFrom, dateTo, year);
                    
                    // Restore button
                    applyBtn.innerHTML = originalText;
                    applyBtn.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses filter.');
                    applyBtn.innerHTML = originalText;
                    applyBtn.disabled = false;
                });
        }

    function resetFilters() {
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        document.getElementById('year').value = '';
        
        // Reload page to show original data
        window.location.href = '/maintenance/analytics';
    }

    function updateChartPeriod(dateFrom, dateTo, year) {
        const periodElement = document.getElementById('chartPeriod');
        let periodText = 'Semua Periode';
        
        if (dateFrom && dateTo) {
            const fromDate = new Date(dateFrom);
            const toDate = new Date(dateTo);
            const fromFormatted = fromDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const toFormatted = toDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            periodText = `${fromFormatted} - ${toFormatted}`;
        } else if (dateFrom) {
            const fromDate = new Date(dateFrom);
            const fromFormatted = fromDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            periodText = `Dari ${fromFormatted}`;
        } else if (dateTo) {
            const toDate = new Date(dateTo);
            const toFormatted = toDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            periodText = `Sampai ${toFormatted}`;
        } else if (year) {
            periodText = `Tahun ${year}`;
        }
        
        periodElement.textContent = periodText;
    }

    function updateCharts(data) {
        console.log('Updating charts with data:', data);
        
        // Update Monthly Breakdown Chart
        if (data.monthlyStats) {
            console.log('Updating monthly stats:', data.monthlyStats);
            monthlyChart.data.labels = data.monthlyStats.map(stat => 
                `${new Date(stat.year, stat.month - 1).toLocaleString('id-ID', { month: 'short' })} ${stat.year}`
            );
            monthlyChart.data.datasets[0].data = data.monthlyStats.map(stat => stat.total_reports);
            monthlyChart.update();
            console.log('Monthly chart updated');
        }

        // Update Machine Breakdown Chart
        if (data.machineBreakdowns) {
            console.log('Updating machine breakdowns:', data.machineBreakdowns);
            machineChart.data.labels = data.machineBreakdowns.map(machine => machine.machine.name);
            machineChart.data.datasets[0].data = data.machineBreakdowns.map(machine => machine.breakdown_count);
            
            // Update colors for new data
            const colors = [
                'rgba(59, 130, 246, 0.8)', 'rgba(239, 68, 68, 0.8)', 'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)',
                'rgba(6, 182, 212, 0.8)', 'rgba(132, 204, 22, 0.8)', 'rgba(249, 115, 22, 0.8)',
                'rgba(99, 102, 241, 0.8)'
            ];
            machineChart.data.datasets[0].backgroundColor = colors.slice(0, data.machineBreakdowns.length);
            machineChart.update();
            console.log('Machine chart updated');
        }

        // Update Event Type Chart
        if (data.eventTypeFrequency) {
            console.log('Updating event type frequency:', data.eventTypeFrequency);
            eventChart.data.labels = data.eventTypeFrequency.map(event => event.name);
            eventChart.data.datasets[0].data = data.eventTypeFrequency.map(event => event.frequency);
            eventChart.update();
            console.log('Event type chart updated');
        }

        // Update Part Usage Chart
        if (data.partUsage) {
            console.log('Updating part usage:', data.partUsage);
            partChart.data.labels = data.partUsage.map(part => part.name);
            partChart.data.datasets[0].data = data.partUsage.map(part => part.total_quantity);
            partChart.data.datasets[1].data = data.partUsage.map(part => part.usage_count);
            partChart.update();
            console.log('Part usage chart updated');
        }

        // Update statistics
        if (data.stats) {
            console.log('Updating statistics:', data.stats);
            document.querySelectorAll('.stat-total-reports').forEach(el => {
                el.textContent = data.stats.total_reports || 0;
            });
            document.querySelectorAll('.stat-avg-repair-time').forEach(el => {
                el.textContent = data.stats.avg_repair_time ? `${Math.round(data.stats.avg_repair_time)} menit` : 'N/A';
            });
            document.querySelectorAll('.stat-top-machine').forEach(el => {
                el.textContent = data.stats.top_machine || 'N/A';
            });
            document.querySelectorAll('.stat-top-event-type').forEach(el => {
                el.textContent = data.stats.top_event_type || 'N/A';
            });
            document.querySelectorAll('.stat-top-part').forEach(el => {
                el.textContent = data.stats.top_part || 'N/A';
            });
            console.log('Statistics updated');
        }

        // Update tables
        updateTables(data);
    }

    function updateTables(data) {
        console.log('Updating tables with data:', data);
        
        // Update machine table
        const machineTableBody = document.querySelector('#machineTable tbody');
        if (machineTableBody) {
            let html = '';
            if (data.machineBreakdowns && data.machineBreakdowns.length > 0) {
                data.machineBreakdowns.forEach(machine => {
                    const downtime = machine.total_downtime_minutes ? Math.round(machine.total_downtime_minutes / 60 * 10) / 10 : 0;
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${machine.machine.name}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${machine.breakdown_count}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${downtime > 0 ? downtime + ' jam' : 'N/A'}
                            </td>
                        </tr>
                    `;
                });
            } else {
                // Show empty state
                html = `
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            Tidak ada data untuk filter yang dipilih
                        </td>
                    </tr>
                `;
            }
            machineTableBody.innerHTML = html;
            console.log('Machine table updated');
        }

        // Update event type table
        const eventTypeTableBody = document.querySelector('#eventTypeTable tbody');
        if (eventTypeTableBody) {
            let html = '';
            if (data.eventTypeFrequency && data.eventTypeFrequency.length > 0) {
                data.eventTypeFrequency.forEach(eventType => {
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${eventType.name}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${eventType.frequency}
                            </td>
                        </tr>
                    `;
                });
            } else {
                // Show empty state
                html = `
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            Tidak ada data untuk filter yang dipilih
                        </td>
                    </tr>
                `;
            }
            eventTypeTableBody.innerHTML = html;
            console.log('Event type table updated');
        }

        // Update part usage table
        const partUsageTableBody = document.querySelector('#partUsageTable tbody');
        if (partUsageTableBody) {
            let html = '';
            if (data.partUsage && data.partUsage.length > 0) {
                data.partUsage.forEach(part => {
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${part.name}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${part.total_quantity}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${part.usage_count}
                            </td>
                        </tr>
                    `;
                });
            } else {
                // Show empty state
                html = `
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            Tidak ada data untuk filter yang dipilih
                        </td>
                    </tr>
                `;
            }
            partUsageTableBody.innerHTML = html;
            console.log('Part usage table updated');
        }
    }

    // Initialize chart period display
    updateChartPeriod('', '', '');
    
    // Add event listeners for filter buttons
    document.getElementById('applyFilterBtn').addEventListener('click', applyFilters);
    document.getElementById('resetFilterBtn').addEventListener('click', resetFilters);
    
    // Function to update export form with current filters
    function updateExportForm(dateFrom, dateTo, year) {
        document.getElementById('export_date_from').value = dateFrom || '';
        document.getElementById('export_date_to').value = dateTo || '';
        document.getElementById('export_year').value = year || '';
    }
    
    // Update export form when filters are applied
    document.getElementById('applyFilterBtn').addEventListener('click', function() {
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        const year = document.getElementById('year').value;
        updateExportForm(dateFrom, dateTo, year);
    });
    
    // Initialize export form with current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const initialDateFrom = urlParams.get('date_from') || '';
    const initialDateTo = urlParams.get('date_to') || '';
    const initialYear = urlParams.get('year') || '';
    
    // Set initial values in filter form
    if (initialDateFrom) document.getElementById('date_from').value = initialDateFrom;
    if (initialDateTo) document.getElementById('date_to').value = initialDateTo;
    if (initialYear) document.getElementById('year').value = initialYear;
    
    // Initialize export form with initial values
    updateExportForm(initialDateFrom, initialDateTo, initialYear);
    
    // Also update export form when page loads with existing filters
    updateExportForm(
        document.getElementById('date_from').value,
        document.getElementById('date_to').value,
        document.getElementById('year').value
    );
});
</script>
@endsection
