<?php

namespace Database\Seeders;

use App\Models\BreakdownReport;
use App\Models\Machine;
use App\Models\User;
use App\Models\EventType;
use App\Models\CauseType;
use App\Models\PartType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BreakdownReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operatorUsers = User::where('role', 'leader_operator')->get();
        $maintenanceUsers = User::where('role', 'leader_teknisi')->get();
        $machines = Machine::all();
        $eventTypes = EventType::all();
        $causeTypes = CauseType::all();
        $partTypes = PartType::all();

        if ($operatorUsers->isEmpty() || $machines->isEmpty()) {
            return;
        }

        $statuses = ['new', 'in_progress', 'done'];
        $shifts = ['1', '2', '3'];
        $lines = ['Line A', 'Line B', 'Line C', 'Line D'];
        $departments = ['Produksi', 'Maintenance', 'Quality Control', 'Engineering'];
        $maintenanceClassifications = ['MISOPE', 'corrective', 'preventive', 'breakdown'];
        $ranks = ['mesin_stop', 'mesin_bisa_jalan', 'pengecualian'];
        $designSources = ['desain_dari_luar', 'desain_dari_internal'];
        $repairActions = ['penggantian_part', 'hanya_adjust', 'overhaul', 'kaizen_mekanik', 'lain_lain'];
        $responsibilities = ['design_workshop', 'supplier_part', 'production_assy', 'operator_mtc', 'other'];
        $machineOperationals = ['yes', 'no'];

        // Buat data dummy untuk 12 bulan terakhir, minimal 50 per bulan = 600+ data
        $totalMonths = 12;
        $reportsPerMonth = 50;
        
        for ($monthOffset = 0; $monthOffset < $totalMonths; $monthOffset++) {
            for ($i = 0; $i < $reportsPerMonth; $i++) {
                $reporter = $operatorUsers->random();
                $machine = $machines->random();
                $status = $statuses[array_rand($statuses)];
                
                // Generate date within the specific month
                $year = Carbon::now()->year;
                $month = Carbon::now()->subMonths($monthOffset)->month;
                $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
                
                $reportedAt = Carbon::create($year, $month, rand(1, $daysInMonth), rand(0, 23), rand(0, 59), 0);
                
                // For older months, adjust year if needed
                if ($monthOffset > 0) {
                    $reportedAt = Carbon::now()->subMonths($monthOffset)->startOfMonth()->addDays(rand(0, 27))->addHours(rand(0, 23))->addMinutes(rand(0, 59));
                }
            
            $breakdownReport = BreakdownReport::create([
                'machine_id' => $machine->id,
                'reporter_id' => $reporter->id,
                'reporter_name' => $reporter->name,
                'department' => $departments[array_rand($departments)],
                'line' => $lines[array_rand($lines)],
                'shift' => $shifts[array_rand($shifts)],
                'machine_number' => 'M' . rand(100, 999),
                'problem_area' => 'Masalah pada bagian ' . ['motor', 'sensor', 'belt', 'bearing', 'controller'][array_rand([0,1,2,3,4])] . ' menyebabkan ' . ['getaran', 'suara aneh', 'tidak berfungsi', 'overheat'][array_rand([0,1,2,3])],
                'maintenance_classification' => $maintenanceClassifications[array_rand($maintenanceClassifications)],
                'rank' => $ranks[array_rand($ranks)],
                'design_source' => $designSources[array_rand($designSources)],
                'repair_action' => $repairActions[array_rand($repairActions)],
                'responsibility' => $responsibilities[array_rand($responsibilities)],
                'responsibility_notes' => rand(0, 1) ? 'Catatan tanggung jawab: ' . ['perlu follow up', 'sudah ditindaklanjuti', 'menunggu part'][array_rand([0,1,2])] : null,
                'status' => $status,
                'reported_at' => $reportedAt,
                'repair_start_at' => $status !== 'new' ? $reportedAt->copy()->addMinutes(rand(15, 120)) : null,
                'repair_end_at' => $status === 'done' ? $reportedAt->copy()->addHours(rand(1, 8)) : null,
                'maintenance_leader_id' => $status !== 'new' && !$maintenanceUsers->isEmpty() ? $maintenanceUsers->random()->id : null,
                'machine_operational' => $status === 'done' ? $machineOperationals[array_rand($machineOperationals)] : null,
                'technician_notes' => $status === 'done' ? 'Teknisi telah melakukan perbaikan dengan ' . ['mengganti part', 'adjust setting', 'membersihkan komponen'][array_rand([0,1,2])] : null,
                ]);
                
                // Tambahkan event types (jenis kerusakan)
                if (!$eventTypes->isEmpty()) {
                    $selectedEvents = $eventTypes->random(rand(1, 3));
                    foreach ($selectedEvents as $event) {
                        $breakdownReport->eventTypes()->attach($event->id);
                    }
                }

                // Tambahkan cause types (penyebab kerusakan)
                if (!$causeTypes->isEmpty()) {
                    $selectedCauses = $causeTypes->random(rand(1, 2));
                    foreach ($selectedCauses as $cause) {
                        $breakdownReport->causeTypes()->attach($cause->id);
                    }
                }

                // Tambahkan part types (part yang diganti)
                if (!$partTypes->isEmpty() && $status === 'done') {
                    $selectedParts = $partTypes->random(rand(1, 3));
                    foreach ($selectedParts as $part) {
                        $breakdownReport->breakdownParts()->create([
                            'part_type_id' => $part->id,
                            'quantity' => rand(1, 5),
                            'notes' => 'Part diganti karena ' . ['rusak', 'aus', 'tidak sesuai spesifikasi'][array_rand([0,1,2])],
                        ]);
                    }
                }
            }
        }
        
        // Buat tambahan data untuk variasi
        $additionalReports = 100;
        for ($i = 0; $i < $additionalReports; $i++) {
            $reporter = $operatorUsers->random();
            $machine = $machines->random();
            $status = $statuses[array_rand($statuses)];
            
            // Random date dalam 2 tahun terakhir
            $reportedAt = Carbon::now()->subDays(rand(0, 730))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $breakdownReport = BreakdownReport::create([
                'machine_id' => $machine->id,
                'reporter_id' => $reporter->id,
                'reporter_name' => $reporter->name,
                'department' => $departments[array_rand($departments)],
                'line' => $lines[array_rand($lines)],
                'shift' => $shifts[array_rand($shifts)],
                'machine_number' => 'M' . rand(100, 999),
                'problem_area' => 'Masalah pada bagian ' . ['motor', 'sensor', 'belt', 'bearing', 'controller'][array_rand([0,1,2,3,4])] . ' menyebabkan ' . ['getaran', 'suara aneh', 'tidak berfungsi', 'overheat'][array_rand([0,1,2,3])],
                'maintenance_classification' => $maintenanceClassifications[array_rand($maintenanceClassifications)],
                'rank' => $ranks[array_rand($ranks)],
                'design_source' => $designSources[array_rand($designSources)],
                'repair_action' => $repairActions[array_rand($repairActions)],
                'responsibility' => $responsibilities[array_rand($responsibilities)],
                'responsibility_notes' => rand(0, 1) ? 'Catatan tanggung jawab: ' . ['perlu follow up', 'sudah ditindaklanjuti', 'menunggu part'][array_rand([0,1,2])] : null,
                'status' => $status,
                'reported_at' => $reportedAt,
                'repair_start_at' => $status !== 'new' ? $reportedAt->copy()->addMinutes(rand(15, 120)) : null,
                'repair_end_at' => $status === 'done' ? $reportedAt->copy()->addHours(rand(1, 8)) : null,
                'maintenance_leader_id' => $status !== 'new' && !$maintenanceUsers->isEmpty() ? $maintenanceUsers->random()->id : null,
                'machine_operational' => $status === 'done' ? $machineOperationals[array_rand($machineOperationals)] : null,
                'technician_notes' => $status === 'done' ? 'Teknisi telah melakukan perbaikan dengan ' . ['mengganti part', 'adjust setting', 'membersihkan komponen'][array_rand([0,1,2])] : null,
            ]);

            // Tambahkan event types (jenis kerusakan)
            if (!$eventTypes->isEmpty()) {
                $selectedEvents = $eventTypes->random(rand(1, 3));
                foreach ($selectedEvents as $event) {
                    $breakdownReport->eventTypes()->attach($event->id);
                }
            }

            // Tambahkan cause types (penyebab kerusakan)
            if (!$causeTypes->isEmpty()) {
                $selectedCauses = $causeTypes->random(rand(1, 2));
                foreach ($selectedCauses as $cause) {
                    $breakdownReport->causeTypes()->attach($cause->id);
                }
            }

            // Tambahkan part types (part yang diganti)
            if (!$partTypes->isEmpty() && $status === 'done') {
                $selectedParts = $partTypes->random(rand(1, 3));
                foreach ($selectedParts as $part) {
                    $breakdownReport->breakdownParts()->create([
                        'part_type_id' => $part->id,
                        'quantity' => rand(1, 5),
                        'notes' => 'Part diganti karena ' . ['rusak', 'aus', 'tidak sesuai spesifikasi'][array_rand([0,1,2])],
                    ]);
                }
            }
        }

    }
}
