<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvestmentPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            // BASIC TIER
            [
                'tier' => 'BASIC',
                'name' => 'BASIC 1',
                'description' => 'Entry level plan for new investors.',
                'price' => 500.00,
                'target_return' => 15000.00,
                'duration_days' => 3, // 3 hours
                'status' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'BASIC',
                'name' => 'BASIC 2',
                'description' => 'Low-tier plan with enhanced yield.',
                'price' => 1000.00,
                'target_return' => 31000.00,
                'duration_days' => 3, // 3 hours
                'status' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'BASIC',
                'name' => 'BASIC 3',
                'description' => 'Moderate return with starter capital.',
                'price' => 1300.00,
                'target_return' => 39000.00,
                'duration_days' => 3, // 3 hours
                'status' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'BASIC',
                'name' => 'BASIC 4',
                'description' => 'High return starter plan.',
                'price' => 1500.00,
                'target_return' => 45000.00,
                'duration_days' => 3, // 3 hours
                'status' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // GOLD TIER
            [
                'tier' => 'GOLD',
                'name' => 'GOLD 1',
                'description' => 'Advanced plan for established investors.',
                'price' => 2000.00,
                'target_return' => 70000.00,
                'duration_days' => 4, // 4 hours
                'status' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'GOLD',
                'name' => 'GOLD 2',
                'description' => 'Premium yield with medium investment range.',
                'price' => 3000.00,
                'target_return' => 105000.00,
                'duration_days' => 4, // 4 hours
                'status' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'GOLD',
                'name' => 'GOLD 3',
                'description' => 'Ultimate gold tier returns.',
                'price' => 4000.00,
                'target_return' => 140000.00,
                'duration_days' => 4, // 4 hours
                'status' => true,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // DIAMOND TIER
            [
                'tier' => 'DIAMOND',
                'name' => 'DIAMOND 1',
                'description' => 'Elite plan with exceptional yield.',
                'price' => 5000.00,
                'target_return' => 200000.00,
                'duration_days' => 5, // 5 hours
                'status' => true,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'DIAMOND',
                'name' => 'DIAMOND 2',
                'description' => 'Prestige level for professional investors.',
                'price' => 7000.00,
                'target_return' => 280000.00,
                'duration_days' => 5, // 5 hours
                'status' => true,
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'DIAMOND',
                'name' => 'DIAMOND 3',
                'description' => 'Highest diamond tier return rate.',
                'price' => 10000.00,
                'target_return' => 400000.00,
                'duration_days' => 5, // 5 hours
                'status' => true,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // VVIP TIER
            [
                'tier' => 'VVIP',
                'name' => 'VVIP LUXURY',
                'description' => 'VVIP exclusive luxury investment tier.',
                'price' => 15000.00,
                'target_return' => 580000.00,
                'duration_days' => 6, // 6 hours
                'status' => true,
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tier' => 'VVIP',
                'name' => 'VVIP ELITE',
                'description' => 'The ultimate VIP Elite return plan.',
                'price' => 20000.00,
                'target_return' => 600000.00,
                'duration_days' => 6, // 6 hours
                'status' => true,
                'sort_order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('investment_plans')->insert($plans);
    }
}
