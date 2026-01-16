<?php

namespace Database\Seeders;

use App\Models\PartType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partTypes = [
            ['name' => 'Cam, Gear'],
            ['name' => 'Rantai, Belt'],
            ['name' => 'Inveter'],
            ['name' => 'Guide'],
            ['name' => 'Counter'],
            ['name' => 'Key / spie'],
            ['name' => 'Clutch'],
            ['name' => 'Conveyor'],
            ['name' => 'Program PLC'],
            ['name' => 'Seal'],
            ['name' => 'Shaft'],
            ['name' => 'Cylinder'],
            ['name' => 'Cylinder switch'],
            ['name' => 'Screw'],
            ['name' => 'Spring'],
            ['name' => 'Slide'],
            ['name' => 'Slip Ring'],
            ['name' => 'Timer'],
            ['name' => 'Chuck'],
            ['name' => 'Trubust'],
            ['name' => 'Nozzle'],
            ['name' => 'Valve'],
            ['name' => 'Heater'],
            ['name' => 'Fuse/Sekring'],
            ['name' => 'Pin'],
            ['name' => 'Filter'],
            ['name' => 'Brake'],
            ['name' => 'Press'],
            ['name' => 'Bearing'],
            ['name' => 'Katrol / Holst'],
            ['name' => 'Baut, Nut'],
            ['name' => 'Pompa'],
            ['name' => 'Motor'],
            ['name' => 'Limit SW'],
            ['name' => 'Relay'],
            ['name' => 'Roller'],
            ['name' => 'Temperature controller'],
            ['name' => 'Thermo couple'],
            ['name' => 'Grease/oil pelumas'],
            ['name' => 'Proximity switch'],
            ['name' => 'Dies, Pisau'],
            ['name' => 'Photoelectric SW'],
            ['name' => 'Gear box'],
            ['name' => 'Fan color'],
            ['name' => 'Pipa, Selang'],
            ['name' => 'Wire, Terminal'],
            ['name' => 'Sistem Hidrolik'],
            ['name' => 'Chiller'],
            ['name' => 'Lain-lain'],
        ];

        foreach ($partTypes as $partType) {
            PartType::create($partType);
        }
    }
}
