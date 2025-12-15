@extends('layouts.app')

@section('title', 'Selesaikan Perbaikan')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Selesaikan Perbaikan</h1>
                <p class="mt-2 text-gray-600">Lengkapi detail perbaikan untuk laporan #{{ $breakdownReport->id }}</p>
            </div>
            <a href="{{ route('maintenance.show', $breakdownReport) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Laporan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Mesin</label>
                    <p class="text-sm text-gray-900">{{ $breakdownReport->machine->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Pelapor</label>
                    <p class="text-sm text-gray-900">{{ $breakdownReport->reporter->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Line</label>
                    <p class="text-sm text-gray-900">{{ $breakdownReport->line }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Deskripsi Masalah</label>
                    <p class="text-sm text-gray-900">{{ $breakdownReport->problem_area }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Repair Form -->
    <form action="{{ route('maintenance.complete-repair', $breakdownReport) }}" method="POST">
        @csrf
        
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Status & Klasifikasi -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Klasifikasi</h3>
                    
                    <!-- Klasifikasi Maintenance -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Klasifikasi Maintenance</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center">
                                <input type="radio" name="maintenance_classification" value="MISOPE" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">MISOPE</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="maintenance_classification" value="corrective" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Corrective Maintenance</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="maintenance_classification" value="preventive" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Preventive Maintenance</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="maintenance_classification" value="breakdown" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Breakdown Maintenance</span>
                            </label>
                        </div>
                    </div>

                    <!-- Mesin Langsung Proses -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Mesin Langsung Proses</h4>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="machine_operational" value="yes" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">YA</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="machine_operational" value="no" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">TIDAK</span>
                            </label>
                        </div>
                    </div>

                    <!-- Rank (Tingkat) -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Rank (Tingkat)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-center">
                                <input type="radio" name="rank" value="mesin_stop" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Mesin Stop</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="rank" value="mesin_bisa_jalan" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Mesin Bisa Jalan</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="rank" value="pengecualian" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Pengecualian</span>
                            </label>
                        </div>
                    </div>

                    <!-- Desain Alat/Mesin -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Desain Alat/Mesin</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center">
                                <input type="radio" name="design_source" value="desain_dari_luar" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Desain dari Luar</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="design_source" value="desain_dari_internal" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Desain dari Internal</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Event Types -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Jenis Kejadian / Kerusakan (41 Item)</h3>
                    <p class="text-sm text-gray-600 mb-4">Pilih jenis kerusakan yang terjadi (bisa lebih dari satu)</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($eventTypes as $event)
                            <label class="flex items-center">
                                <input type="checkbox" name="event_types[]" value="{{ $event->id }}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">{{ $event->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('event_types')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tindakan Perbaikan -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tindakan Perbaikan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <label class="flex items-center">
                            <input type="radio" name="repair_action" value="penggantian_part" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Penggantian Part</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="repair_action" value="hanya_adjust" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Hanya Adjust</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="repair_action" value="overhaul" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Overhaul</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="repair_action" value="kaizen_mekanik" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Kaizen Mekanik</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="repair_action" value="lain_lain" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Lain-lain</span>
                        </label>
                    </div>
                    @error('repair_action')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cause Types -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Penyebab Kerusakan (25 Item)</h3>
                    <p class="text-sm text-gray-600 mb-4">Pilih penyebab kerusakan (bisa lebih dari satu)</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($causeTypes as $cause)
                            <label class="flex items-center">
                                <input type="checkbox" name="cause_types[]" value="{{ $cause->id }}" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">{{ $cause->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('cause_types')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parts Replaced -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Part yang Diganti (50 Jenis)</h3>
                    <p class="text-sm text-gray-600 mb-4">Tambahkan part yang diganti selama perbaikan</p>
                    
                    <div id="parts-container" class="space-y-4">
                        <!-- Dynamic parts will be added here -->
                        <div class="part-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            <div class="md:col-span-5">
                                <label for="part_type_id_0" class="block text-sm font-medium text-gray-700">Part</label>
                                <select name="parts[0][part_type_id]" id="part_type_id_0" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Part</option>
                                    @foreach($partTypes as $part)
                                        <option value="{{ $part->id }}">{{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="quantity_0" class="block text-sm font-medium text-gray-700">Qty</label>
                                <input type="number" name="parts[0][quantity]" id="quantity_0" min="1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-4">
                                <label for="notes_0" class="block text-sm font-medium text-gray-700">Catatan</label>
                                <input type="text" name="parts[0][notes]" id="notes_0" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="Catatan tambahan">
                            </div>
                            <div class="md:col-span-1">
                                <button type="button" onclick="removePart(this)" class="text-red-600 hover:text-red-800">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" onclick="addPart()" class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Part
                    </button>
                </div>

                <!-- Responsibility -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tanggung Jawab</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="responsibility" class="block text-sm font-medium text-gray-700">Pilih Tanggung Jawab</label>
                            <select name="responsibility" id="responsibility" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Tanggung Jawab</option>
                                <option value="design_workshop">Design/Workshop</option>
                                <option value="supplier_part">Supplier Part</option>
                                <option value="production_assy">Produksi/Assy</option>
                                <option value="operator_mtc">Operator MTC</option>
                                <option value="other">Lain-lain</option>
                            </select>
                            @error('responsibility')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="responsibility_notes" class="block text-sm font-medium text-gray-700">Catatan Tanggung Jawab</label>
                            <textarea name="responsibility_notes" id="responsibility_notes" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                      placeholder="Catatan tambahan mengenai tanggung jawab"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Technician Notes -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan Teknisi</h3>
                    <textarea name="technician_notes" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                              placeholder="Tuliskan catatan tambahan mengenai perbaikan yang dilakukan..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6 mt-8">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Selesaikan Perbaikan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let partCounter = 1;

function addPart() {
    const container = document.getElementById('parts-container');
    const newPart = document.createElement('div');
    newPart.className = 'part-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end';
    newPart.innerHTML = `
        <div class="md:col-span-5">
            <label class="block text-sm font-medium text-gray-700">Part</label>
            <select name="parts[${partCounter}][part_type_id]" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Pilih Part</option>
                @foreach($partTypes as $part)
                    <option value="{{ $part->id }}">{{ $part->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Qty</label>
            <input type="number" name="parts[${partCounter}][quantity]" min="1" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="md:col-span-4">
            <label class="block text-sm font-medium text-gray-700">Catatan</label>
            <input type="text" name="parts[${partCounter}][notes]" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                   placeholder="Catatan tambahan">
        </div>
        <div class="md:col-span-1">
            <button type="button" onclick="removePart(this)" class="text-red-600 hover:text-red-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    `;
    container.appendChild(newPart);
    partCounter++;
}

function removePart(button) {
    const partRow = button.closest('.part-row');
    if (document.querySelectorAll('.part-row').length > 1) {
        partRow.remove();
    }
}
</script>
@endsection
