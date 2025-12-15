<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventTypes = [
            ['name' => 'Kerusakan'],
            ['name' => 'Seret'],
            ['name' => 'Bocor'],
            ['name' => 'Lain-lain'],
            ['name' => 'Penyumbatan'],
            ['name' => 'Aus'],
            ['name' => 'Mining'],
            ['name' => 'Terbakar'],
            ['name' => 'Berubah bentuk'],
            ['name' => 'Longgar'],
            ['name' => 'Contact NG'],
            ['name' => 'Terlalu sering dipakai'],
            ['name' => 'Patah'],
            ['name' => 'Leeet'],
            ['name' => 'Pendek'],
            ['name' => 'Keluar (nonggol)'],
            ['name' => 'Akurasi NG'],
            ['name' => 'Karat'],
            ['name' => 'Cutting'],
            ['name' => 'Tidak sejajar'],
            ['name' => 'Suhu abnormal'],
            ['name' => 'Isolasi NG'],
            ['name' => 'Crack'],
            ['name' => 'Putus'],
            ['name' => 'Suara aneh'],
            ['name' => 'Retak'],
            ['name' => 'Mengelupas'],
            ['name' => 'Pecah'],
            ['name' => 'Sobek'],
            ['name' => 'Tercampur benda lain'],
            ['name' => 'Kotor'],
            ['name' => 'Elongasi & Penyusutan'],
            ['name' => 'Terpanggang (hangus)'],
            ['name' => 'Sensitivitas NG'],
            ['name' => 'Stroke terkunci'],
            ['name' => 'Slip'],
            ['name' => 'Getar'],
            ['name' => 'Tekanan abnormal'],
            ['name' => 'Grounding NG'],
            ['name' => 'Meledak'],
            ['name' => 'Hasil NG'],
        ];

        foreach ($eventTypes as $eventType) {
            EventType::create($eventType);
        }
    }
}
