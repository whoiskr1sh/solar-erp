<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\User;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        // Remove all leads (avoid FK constraint error)
        Lead::query()->delete();

        // Add 10 leads for the first active user (login user)
        $user = User::where('is_active', true)->first();
        $sources = ['website', 'indiamart', 'referral', 'meta_ads', 'justdial', 'cold_call'];
        $statuses = ['interested', 'partially_interested', 'not_reachable', 'not_interested', 'not_answered'];
        $priorities = ['high', 'medium', 'urgent', 'low'];
        $companies = ['Solar Solutions Pvt Ltd', 'TechCorp Industries', 'Green Energy Solutions', 'Mehta Manufacturing', 'City Hospital', 'Delhi Public School', 'Grand Hotel', 'Textile Factory'];

        if ($user) {
            for ($i = 1; $i <= 10; $i++) {
                Lead::create([
                    'name' => $user->name . " Lead $i",
                    'email' => strtolower(str_replace(' ', '', $user->name)) . "+lead$i@solarerp.com",
                    'phone' => '+91-98765' . str_pad($i, 5, '43210', STR_PAD_LEFT),
                    'company' => $companies[$i % count($companies)],
                    'address' => "$i, Main Road, City $i",
                    'source' => $sources[$i % count($sources)],
                    'status' => $statuses[$i % count($statuses)],
                    'priority' => $priorities[$i % count($priorities)],
                    'estimated_value' => rand(100000, 2000000),
                    'expected_close_date' => now()->addDays(rand(10, 60)),
                    'assigned_user_id' => null,
                    'created_by' => $user->id,
                    'notes' => "Auto-generated lead $i for user {$user->name}.",
                    'follow_up_date' => now()->addDays(rand(5, 20)),
                    'last_follow_up_at' => now()->subDays(rand(0, 10)),
                ]);
            }
        }
    }
}
