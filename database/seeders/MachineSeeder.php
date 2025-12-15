<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machines = [
            'Extruder',
            'Planetary',
            'Winding',
            'Coilling',
            'Rolling',
            'Endless',
            'Drawing',
            'Stranding',
            'Buncher',
            'Rotor',
            'Tester Outer',
            'Tester Inner',
            'Loss Cutter',
            'Tester Robot',
            'Conveyor',
            'Hydrolik Press',
            'Push Pull',
            'Tester',
            'Grease Gun',
            'Hot Marking',
            'Air Press',
            'Grease Pump',
            'Insert AQ',
            'Swaging',
            'Welding',
            'Staoking',
            'Linner Kembang',
            'Staoking AC-03',
            'MC Rol GH',
            'Roll GH',
            'Mesin Stecking',
            'DCM',
            'Tester Auto',
            'Roll GC',
            'Roll GH',
            'Linner Cutting',
            'Solenoid',
            'Rippetting',
        ];

        $lines = ['Line A', 'Line B', 'Line C', 'Line D'];
        
        foreach ($machines as $machineName) {
            Machine::create([
                'name' => $machineName,
                'line' => $lines[array_rand($lines)],
                'machine_number' => 'M' . rand(1000, 9999),
                'description' => 'Mesin ' . $machineName . ' untuk produksi',
                'status' => 'active',
            ]);
        }
    }
}
