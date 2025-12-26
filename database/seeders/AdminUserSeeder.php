<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Admin user created successfully.');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: password123');
        } else {
            $this->command->info('Admin user already exists.');
        }

        // Create additional test users for each role if they don't exist
        $users = [
            [
                'name' => 'Maintenance Leader',
                'email' => 'maintenance@example.com',
                'password' => Hash::make('password123'),
                'role' => 'leader_teknisi',
            ],
            [
                'name' => 'Operator Leader',
                'email' => 'operator@example.com',
                'password' => Hash::make('password123'),
                'role' => 'leader_operator',
            ],
        ];

        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create($userData);
                $this->command->info("User {$userData['name']} created.");
            }
        }
    }
}
