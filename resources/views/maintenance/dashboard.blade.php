@extends('layouts.app')

@section('title', '<span class="text-white">Dashboard Maintenance</span>')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-navy-900">Dashboard Leader Teknisi - Manufacture Engineering</h1>
        <p class="mt-2 text-navy-600">Selamat datang, {{ auth()->user()->name }}! Kelola semua laporan kerusakan mesin di sini.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-navy-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-teal-500 rounded-lg p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-navy-600 truncate">Total Laporan</dt>
                            <dd class="text-lg font-medium text-navy-900">{{ $stats['total_reports'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-navy-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-navy-600 truncate">Laporan Baru</dt>
                            <dd class="text-lg font-medium text-navy-900">{{ $stats['new_reports'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-navy-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-500 rounded-lg p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-navy-600 truncate">Sedang Diperbaiki</dt>
                            <dd class="text-lg font-medium text-navy-900">{{ $stats['in_progress_reports'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-navy-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-navy-600 truncate">Selesai</dt>
                            <dd class="text-lg font-medium text-navy-900">{{ $stats['done_reports'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <div class="bg-white shadow-lg rounded-xl border border-navy-200">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-navy-900 mb-4">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('maintenance.reports') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Lihat Semua Laporan
                    </a>
                    <a href="{{ route('maintenance.analytics') }}" class="inline-flex items-center px-4 py-2 border border-navy-300 text-sm font-medium rounded-lg text-navy-700 bg-white hover:bg-navy-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Analitik & Grafik
                    </a>
                </div>
                
                <!-- In Progress Reports Quick Actions -->
                @php
                    $inProgressReports = \App\Models\BreakdownReport::inProgress()->with('machine')->limit(3)->get();
                @endphp
                @if($inProgressReports->count() > 0)
                    <div class="mt-6 pt-6 border-t border-navy-200">
                        <h4 class="text-md font-medium text-navy-900 mb-3">Laporan Sedang Diperbaiki</h4>
                        <div class="space-y-3">
                            @foreach($inProgressReports as $report)
                                <div class="flex items-center justify-between bg-orange-50 p-3 rounded-md">
                                    <div>
                                        <p class="text-sm font-medium text-navy-900">#{{ $report->id }} - {{ $report->machine->name }}</p>
                                        <p class="text-xs text-navy-600">Dimulai: {{ $report->repair_start_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('maintenance.show', $report) }}" class="inline-flex items-center px-3 py-1 border border-teal-600 text-xs font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                                            Lihat Detail
                                        </a>
                                        <a href="{{ route('maintenance.show-complete-form', $report) }}" class="inline-flex items-center px-3 py-1 border border-teal-600 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            Selesaikan
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            @if($stats['in_progress_reports'] > 3)
                                <div class="text-center">
                                    <a href="{{ route('maintenance.reports') }}?status=in_progress" class="text-sm text-teal-600 hover:text-teal-900">
                                        Lihat semua {{ $stats['in_progress_reports'] }} laporan yang sedang diperbaiki →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-8">
        <div class="bg-white shadow-lg rounded-xl border border-navy-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-navy-700">Filter Laporan</h4>
                    <p class="text-xs text-navy-500">Filter laporan berdasarkan status</p>
                </div>
                <form method="GET" action="{{ route('maintenance.dashboard') }}" class="flex items-center space-x-4">
                    <div>
                        <label for="status" class="sr-only">Status</label>
                        <select id="status" name="status" class="rounded-lg border-navy-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">Semua Status</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Diperbaiki</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-teal-600 hover:bg-teal-700">
                            Terapkan
                        </button>
                        <a href="{{ route('maintenance.dashboard') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-navy-300 text-sm font-medium rounded-lg text-navy-700 bg-white hover:bg-navy-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white shadow-lg rounded-xl border border-navy-200">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-navy-900 mb-4">Laporan Terbaru</h3>
            
            @if($recentReports->count() > 0)
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-navy-200">
                        <thead class="bg-navy-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-navy-700 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-navy-700 uppercase tracking-wider">Mesin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-navy-700 uppercase tracking-wider">Pelapor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-navy-700 uppercase tracking-wider">Status</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-navy-100">
                            @foreach($recentReports as $report)
                                <tr class="hover:bg-navy-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-navy-900">
                                        {{ $report->reported_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-navy-900">
                                        {{ $report->machine->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-navy-900">
                                        {{ $report->reporter->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'new' => 'bg-yellow-100 text-yellow-800',
                                                'in_progress' => 'bg-orange-100 text-orange-800',
                                                'done' => 'bg-green-100 text-green-800'
                                            ];
                                            $statusLabels = [
                                                'new' => 'Baru',
                                                'in_progress' => 'Sedang Diperbaiki',
                                                'done' => 'Selesai'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$report->status] }}">
                                            {{ $statusLabels[$report->status] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('maintenance.show', $report) }}" class="text-teal-600 hover:text-teal-900">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-navy-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-navy-900">Belum ada laporan</h3>
                    <p class="mt-1 text-sm text-navy-500">Tidak ada laporan kerusakan mesin yang perlu ditangani saat ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
