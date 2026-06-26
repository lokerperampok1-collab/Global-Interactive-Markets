<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@globalinteractivemarkets.com',
            'password' => bcrypt('admin123'),
            'phone' => '+1234567890',
            'role' => 'admin',
            'country_name' => 'United States',
            'country_code' => 'US',
            'currency_code' => 'USD',
            'currency_symbol' => '$',
            'status_kyc' => 'approved',
            'is_withdraw_unlocked' => true,
        ]);

        // Create Admin Wallet
        $admin->wallet()->create([
            'currency' => 'USD',
            'balance' => 5000.00,
        ]);

        // Call Investment Plan Seeder
        $this->call(InvestmentPlanSeeder::class);
    }
}
