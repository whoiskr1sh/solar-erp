<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quotation;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing quotations to prevent duplicates
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('quotations')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $clients = Lead::all();
        $projects = Project::all();
        $users = User::all();
        
        $quotations = [
            [
                'quotation_number' => 'QUOT-2024-001',
                'quotation_date' => now()->subDays(15),
                'valid_until' => now()->addDays(15),
                'client_id' => $clients->first()->id,
                'project_id' => $projects->first()->id ?? null,
                'subtotal' => 500000,
                'tax_amount' => 90000,
                'total_amount' => 590000,
                'status' => 'sent',
                'notes' => 'Complete solar panel installation package including panels, inverters, and monitoring system.',
                'terms_conditions' => 'Payment terms: 50% advance, 50% on completion. Warranty: 5 years on panels, 2 years on inverters.',
                'created_by' => $users->first()->id,
            ],
            [
                'quotation_number' => 'QUOT-2024-002',
                'quotation_date' => now()->subDays(10),
                'valid_until' => now()->addDays(20),
                'client_id' => $clients->skip(1)->first()->id ?? $clients->first()->id,
                'project_id' => $projects->skip(1)->first()->id ?? null,
                'subtotal' => 750000,
                'tax_amount' => 135000,
                'total_amount' => 885000,
                'status' => 'accepted',
                'notes' => 'Commercial solar installation for office building with net metering setup.',
                'terms_conditions' => 'Payment terms: 30% advance, 40% on delivery, 30% on completion. Installation timeline: 4-6 weeks.',
                'created_by' => $users->first()->id,
            ],
            [
                'quotation_number' => 'QUOT-2024-003',
                'quotation_date' => now()->subDays(5),
                'valid_until' => now()->addDays(25),
                'client_id' => $clients->skip(2)->first()->id ?? $clients->first()->id,
                'project_id' => null,
                'subtotal' => 250000,
                'tax_amount' => 45000,
                'total_amount' => 295000,
                'status' => 'draft',
                'notes' => 'Residential solar system for 3BHK house with rooftop mounting.',
                'terms_conditions' => 'Payment terms: 40% advance, 60% on completion. Free maintenance for first year.',
                'created_by' => $users->skip(1)->first()->id ?? $users->first()->id,
            ],
            [
                'quotation_number' => 'QUOT-2024-004',
                'quotation_date' => now()->subDays(20),
                'valid_until' => now()->subDays(5),
                'client_id' => $clients->skip(3)->first()->id ?? $clients->first()->id,
                'project_id' => $projects->skip(2)->first()->id ?? null,
                'subtotal' => 1200000,
                'tax_amount' => 216000,
                'total_amount' => 1416000,
                'status' => 'expired',
                'notes' => 'Large scale industrial solar power plant with advanced monitoring system.',
                'terms_conditions' => 'Payment terms: 25% advance, 50% on delivery, 25% on commissioning. Project timeline: 3-4 months.',
                'created_by' => $users->first()->id,
            ],
            [
                'quotation_number' => 'QUOT-2024-005',
                'quotation_date' => now()->subDays(8),
                'valid_until' => now()->addDays(22),
                'client_id' => $clients->skip(4)->first()->id ?? $clients->first()->id,
                'project_id' => null,
                'subtotal' => 800000,
                'tax_amount' => 144000,
                'total_amount' => 944000,
                'status' => 'rejected',
                'notes' => 'Hospital solar backup system with battery storage for critical equipment.',
                'terms_conditions' => 'Payment terms: 30% advance, 70% on completion. 24/7 support included.',
                'created_by' => $users->skip(1)->first()->id ?? $users->first()->id,
            ],
        ];

        foreach ($quotations as $quotationData) {
            Quotation::create($quotationData);
        }
    }
}
