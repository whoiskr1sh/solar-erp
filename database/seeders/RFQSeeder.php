<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RFQ;
use App\Models\RFQItem;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class RFQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        if ($projects->isEmpty() || $users->isEmpty()) {
            $this->command->info('Skipping RFQSeeder: No projects or users found. Please run ProjectSeeder and UserSeeder first.');
            return;
        }

        $statuses = ['draft', 'sent', 'received', 'evaluated', 'awarded', 'cancelled'];
        $descriptions = [
            'RFQ for solar panel procurement',
            'RFQ for inverter and electrical components',
            'RFQ for mounting structures',
            'RFQ for battery storage systems',
            'RFQ for monitoring equipment',
            'RFQ for installation services',
            'RFQ for maintenance services',
        ];

        $itemNames = [
            'Solar Panels', 'Inverters', 'Mounting Structures', 'Batteries', 'Cables',
            'Connectors', 'Monitoring Systems', 'Safety Equipment', 'Tools', 'Maintenance Kits'
        ];

        $units = ['pcs', 'kg', 'meters', 'sq ft', 'box', 'set', 'liter', 'ton'];

        for ($i = 0; $i < 6; $i++) {
            $project = $projects->random();
            $createdBy = $users->random();
            $status = $statuses[array_rand($statuses)];
            $description = $descriptions[array_rand($descriptions)];
            
            $rfqDate = Carbon::now()->subDays(rand(1, 30));
            $quotationDueDate = $rfqDate->copy()->addDays(rand(7, 30));
            $validUntil = $quotationDueDate->copy()->addDays(rand(30, 90));

            $rfq = RFQ::create([
                'rfq_number' => 'RFQ-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'project_id' => $project->id,
                'rfq_date' => $rfqDate,
                'quotation_due_date' => $quotationDueDate,
                'valid_until' => $validUntil,
                'status' => $status,
                'description' => $description,
                'terms_conditions' => 'Standard terms and conditions apply. All quotations must be valid for 90 days.',
                'delivery_terms' => 'Delivery within 30 days of order confirmation.',
                'payment_terms' => 'Net 30 days from delivery.',
                'estimated_budget' => rand(100000, 1000000) / 100, // Budget between 1000 and 10000
                'created_by' => $createdBy->id,
            ]);

            // Add items to the RFQ
            $itemCount = rand(2, 4);
            for ($j = 0; $j < $itemCount; $j++) {
                $itemName = $itemNames[array_rand($itemNames)];
                $quantity = rand(10, 100);
                $unit = $units[array_rand($units)];
                $estimatedPrice = rand(1000, 10000) / 100; // Price between 10 and 100

                RFQItem::create([
                    'rfq_id' => $rfq->id,
                    'item_name' => $itemName,
                    'description' => 'High quality ' . strtolower($itemName) . ' for solar installation.',
                    'specifications' => 'Standard specifications as per project requirements.',
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'estimated_price' => $estimatedPrice,
                    'remarks' => (rand(0, 1) ? 'Urgent requirement for project timeline.' : null),
                ]);
            }

            // Add approval details if status is approved
            if ($status === 'approved') {
                $approver = $users->random();
                $rfq->update([
                    'approved_by' => $approver->id,
                    'approved_at' => Carbon::now()->subDays(rand(1, 10)),
                    'approval_notes' => 'Approved for procurement.',
                ]);
            }
        }

        $this->command->info('RFQs seeded successfully!');
    }
}