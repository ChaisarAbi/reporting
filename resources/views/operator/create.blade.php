@extends('layouts.app')

@section('title', 'Buat Laporan Kerusakan')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Buat Laporan Kerusakan Baru</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Isi formulir di bawah ini untuk melaporkan kerusakan mesin. Laporan akan dikirim ke Leader Teknisi untuk ditindaklanjuti.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('operator.store') }}" method="POST">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- Reporter Name (Manual Input) -->
                        <div>
                            <label for="reporter_name" class="block text-sm font-medium text-gray-700">Nama Pelapor</label>
                            <input type="text" name="reporter_name" id="reporter_name" required value="{{ old('reporter_name', auth()->user()->name) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Masukkan nama pelapor (default: nama akun yang login)</p>
                            @error('reporter_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Machine Selection -->
                        <div>
                            <label for="machine_id" class="block text-sm font-medium text-gray-700">Nama Mesin</label>
                            <select id="machine_id" name="machine_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Pilih Mesin</option>
                                @foreach($machines as $machine)
                                    <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                                        {{ $machine->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('machine_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department and Line -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700">Nama Bagian</label>
                                <input type="text" name="department" id="department" required value="{{ old('department') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('department')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="line" class="block text-sm font-medium text-gray-700">Line</label>
                                <input type="text" name="line" id="line" required value="{{ old('line') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('line')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Shift and Machine Number -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="shift" class="block text-sm font-medium text-gray-700">Shift</label>
                                <select id="shift" name="shift" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Shift</option>
                                    <option value="1" {{ old('shift') == '1' ? 'selected' : '' }}>Shift 1</option>
                                    <option value="2" {{ old('shift') == '2' ? 'selected' : '' }}>Shift 2</option>
                                </select>
                                @error('shift')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="machine_number" class="block text-sm font-medium text-gray-700">Nomor Mesin / Pos</label>
                                <input type="text" name="machine_number" id="machine_number" value="{{ old('machine_number') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('machine_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Problem Area -->
                        <div>
                            <label for="problem_area" class="block text-sm font-medium text-gray-700">Bagian yang Bermasalah</label>
                            <textarea id="problem_area" name="problem_area" rows="4" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Jelaskan secara detail bagian mesin yang bermasalah dan gejala kerusakan yang terjadi...">{{ old('problem_area') }}</textarea>
                            @error('problem_area')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kirim Laporan
                        </button>
                        <a href="{{ route('operator.dashboard') }}" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
