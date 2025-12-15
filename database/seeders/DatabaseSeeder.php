<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users
        User::create([
            'name' => 'Leader Operator 1',
            'email' => 'operator@example.com',
            'password' => Hash::make('password'),
            'role' => 'leader_operator',
        ]);

        User::create([
            'name' => 'Leader Teknisi 1',
            'email' => 'teknisi@example.com',
            'password' => Hash::make('password'),
            'role' => 'leader_teknisi',
        ]);

        // Create additional users for testing
        User::create([
            'name' => 'Operator Test',
            'email' => 'operator@test.com',
            'password' => Hash::make('password'),
            'role' => 'leader_operator',
        ]);

        User::create([
            'name' => 'Maintenance Test',
            'email' => 'maintenance@test.com',
            'password' => Hash::make('password'),
            'role' => 'leader_teknisi',
        ]);

        // Seed master data
        $this->call([
            EventTypeSeeder::class,
            CauseTypeSeeder::class,
            PartTypeSeeder::class,
            MachineSeeder::class,
            BreakdownReportSeeder::class,
        ]);
    }
}
