<?php

namespace Database\Seeders;

use App\Models\CauseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CauseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $causeTypes = [
            ['name' => 'Part NG'],
            ['name' => 'Adjust NG'],
            ['name' => 'Life time tool'],
            ['name' => 'Over load'],
            ['name' => 'Pipa NG'],
            ['name' => 'Lain-lain'],
            ['name' => 'Penyebab tidak jelas'],
            ['name' => 'Struktur NG'],
            ['name' => 'Kebersihan NG'],
            ['name' => 'Kabel listrik NG'],
            ['name' => 'Operasi NG'],
            ['name' => 'Salah Perakitan'],
            ['name' => 'Rangkaian Listrik NG'],
            ['name' => 'Penyebab langsung'],
            ['name' => 'Instalasi NG'],
            ['name' => 'Pembuatan NG'],
            ['name' => 'Pelumasan NG'],
            ['name' => 'Temporary Action'],
            ['name' => 'Pengecekan NG'],
            ['name' => 'Pengambilan waktu life time NG'],
            ['name' => 'Sensitivitas NG'],
            ['name' => 'Plan NG'],
            ['name' => 'Keterlambatan laporan abnormal'],
            ['name' => 'Instruksi NG'],
            ['name' => 'Salah Drawing'],
        ];

        foreach ($causeTypes as $causeType) {
            CauseType::create($causeType);
        }
    }
}
