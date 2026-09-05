<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('SEED_ADMIN_EMAIL', 'admin@parti2026.com');
        $password = env('SEED_ADMIN_PASSWORD', 'changeme123');

        // Check if the user already exists to prevent duplicate seeding issues
        if (!User::where('email', $email)->exists()) {
            User::create([
                'name' => 'Superadmin',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'SUPERADMIN',
                'is_active' => true,
                'must_change_password' => false,
            ]);
        }
    }
}
