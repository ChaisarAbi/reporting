@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-navy-900 mb-2">Detail Laporan Kerusakan</h1>
                <p class="text-lg text-navy-600">Informasi lengkap laporan kerusakan mesin.</p>
            </div>
            <a href="{{ route('operator.reports') }}" class="inline-flex items-center px-6 py-3 border border-navy-300 text-sm font-medium rounded-xl text-navy-700 bg-white hover:bg-navy-50 hover:border-navy-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 shadow-lg">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Report Information -->
    <div class="bg-white shadow-2xl rounded-2xl mb-8 border border-navy-200">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-xl font-semibold text-navy-900 mb-6">Informasi Laporan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Tanggal Laporan</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->reported_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Mesin</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->machine->name }}</p>
                </div>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Bagian</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->department }}</p>
                </div>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Line</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->line }}</p>
                </div>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Shift</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->shift }}</p>
                </div>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Nomor Mesin/Pos</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->machine_number ?? '-' }}</p>
                </div>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Status</label>
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
                    <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$breakdownReport->status] }}">
                        {{ $statusLabels[$breakdownReport->status] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Problem Description -->
    <div class="bg-white shadow-2xl rounded-2xl mb-8 border border-navy-200">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-xl font-semibold text-navy-900 mb-6">Bagian yang Bermasalah</h3>
            <div class="bg-navy-50 rounded-xl p-6 border border-navy-200">
                <p class="text-base text-navy-900 whitespace-pre-line leading-relaxed">{{ $breakdownReport->problem_area }}</p>
            </div>
        </div>
    </div>

    <!-- Maintenance Information (if available) -->
    @if($breakdownReport->status === 'done' || $breakdownReport->status === 'in_progress')
    <div class="bg-white shadow-2xl rounded-2xl mb-8 border border-navy-200">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-xl font-semibold text-navy-900 mb-6">Informasi Perbaikan</h3>

            @if($breakdownReport->repair_start_at)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Mulai Perbaikan</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->repair_start_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($breakdownReport->repair_end_at)
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    <label class="block text-sm font-medium text-navy-600">Selesai Perbaikan</label>
                    <p class="mt-2 text-base text-navy-900 font-medium">{{ $breakdownReport->repair_end_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
            @endif

            @if($breakdownReport->eventTypes->count() > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-navy-600 mb-4">Jenis Kerusakan</label>
                <div class="space-y-3">
                    @foreach($breakdownReport->eventTypes as $eventType)
                    <div class="flex items-center bg-navy-50 rounded-xl p-4 border border-navy-200">
                        <svg class="h-5 w-5 text-teal-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-base text-navy-900 font-medium">{{ $eventType->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($breakdownReport->causeTypes->count() > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-navy-600 mb-4">Penyebab Kerusakan</label>
                <div class="space-y-3">
                    @foreach($breakdownReport->causeTypes as $causeType)
                    <div class="flex items-center bg-navy-50 rounded-xl p-4 border border-navy-200">
                        <svg class="h-5 w-5 text-teal-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-base text-navy-900 font-medium">{{ $causeType->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($breakdownReport->breakdownParts->count() > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-navy-600 mb-4">Part yang Diganti</label>
                <div class="overflow-x-auto bg-navy-50 rounded-xl border border-navy-200">
                    <table class="min-w-full divide-y divide-navy-200">
                        <thead class="bg-navy-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-navy-700 uppercase tracking-wider">Part</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-navy-700 uppercase tracking-wider">Qty</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-navy-700 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-navy-100">
                            @foreach($breakdownReport->breakdownParts as $part)
                            <tr class="hover:bg-navy-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-navy-900 font-medium">
                                    {{ $part->partType->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-navy-700">
                                    {{ $part->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-navy-700">
                                    {{ $part->notes ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($breakdownReport->responsibility)
            <div class="mb-6">
                <label class="block text-sm font-medium text-navy-600 mb-4">Tanggung Jawab</label>
                <div class="bg-navy-50 rounded-xl p-4 border border-navy-200">
                    @php
                        $responsibilityLabels = [
                            'design_workshop' => 'Design/Workshop',
                            'supplier_part' => 'Supplier Part',
                            'production_assy' => 'Produksi/Assy',
                            'operator_mtc' => 'Operator MTC',
                            'other' => 'Lain-lain'
                        ];
                    @endphp
                    <p class="text-base text-navy-900 font-medium">{{ $responsibilityLabels[$breakdownReport->responsibility] ?? $breakdownReport->responsibility }}</p>
                    @if($breakdownReport->responsibility_notes)
                        <p class="mt-2 text-sm text-navy-700">{{ $breakdownReport->responsibility_notes }}</p>
                    @endif
                </div>
            </div>
            @endif

            @if($breakdownReport->maintenance_notes)
            <div>
                <label class="block text-sm font-medium text-navy-600 mb-4">Catatan Teknisi</label>
                <div class="bg-navy-50 rounded-xl p-6 border border-navy-200">
                    <p class="text-base text-navy-900 whitespace-pre-line leading-relaxed">{{ $breakdownReport->maintenance_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('operator.reports') }}" class="inline-flex items-center px-6 py-3 border border-navy-300 text-sm font-medium rounded-xl text-navy-700 bg-white hover:bg-navy-50 hover:border-navy-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200 shadow-lg">
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection
