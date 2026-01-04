<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class PurchaseRequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        if ($projects->isEmpty() || $users->isEmpty()) {
            $this->command->info('Skipping PurchaseRequisitionSeeder: No projects or users found. Please run ProjectSeeder and UserSeeder first.');
            return;
        }

        $statuses = ['draft', 'submitted', 'approved', 'rejected', 'converted_to_po', 'cancelled'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $purposes = [
            'Solar panel installation materials',
            'Electrical components for inverter setup',
            'Mounting structure materials',
            'Safety equipment and tools',
            'Cables and connectors',
            'Battery storage components',
            'Monitoring system equipment',
            'Maintenance tools and supplies',
        ];

        $itemNames = [
            'Solar Panels', 'Inverters', 'Mounting Structures', 'Batteries', 'Cables',
            'Connectors', 'Safety Harnesses', 'Multimeters', 'Drills', 'Crane Services',
            'Concrete', 'Steel Beams', 'Insulation Material', 'Paint', 'Cleaning Supplies'
        ];

        $units = ['pcs', 'kg', 'meters', 'sq ft', 'box', 'set', 'liter', 'ton'];

        for ($i = 0; $i < 8; $i++) {
            $project = $projects->random();
            $requester = $users->random();
            $status = $statuses[array_rand($statuses)];
            $priority = $priorities[array_rand($priorities)];
            $purpose = $purposes[array_rand($purposes)];
            
            $requisitionDate = Carbon::now()->subDays(rand(1, 30));
            $requiredDate = $requisitionDate->copy()->addDays(rand(7, 45));

            $pr = PurchaseRequisition::create([
                'pr_number' => 'PR-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'project_id' => $project->id,
                'requisition_date' => $requisitionDate,
                'required_date' => $requiredDate,
                'priority' => $priority,
                'status' => $status,
                'purpose' => $purpose,
                'justification' => 'Required for ' . $project->name . ' project completion. Essential materials for solar installation.',
                'estimated_total' => 0, // Will be calculated from items
                'requested_by' => $requester->id,
            ]);

            // Add items to the requisition
            $itemCount = rand(2, 5);
            $totalAmount = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $itemName = $itemNames[array_rand($itemNames)];
                $quantity = rand(10, 100);
                $unitPrice = rand(500, 5000) / 100; // Price between 5 and 50
                $unit = $units[array_rand($units)];
                $itemTotal = $quantity * $unitPrice;
                $totalAmount += $itemTotal;

                PurchaseRequisitionItem::create([
                    'purchase_requisition_id' => $pr->id,
                    'item_name' => $itemName,
                    'description' => 'High quality ' . strtolower($itemName) . ' for solar installation.',
                    'specifications' => 'Standard specifications as per project requirements.',
                    'quantity' => $quantity,
                    'estimated_unit_price' => $unitPrice,
                    'estimated_total_price' => $itemTotal,
                    'unit' => $unit,
                    'remarks' => (rand(0, 1) ? 'Urgent requirement for project timeline.' : null),
                ]);
            }

            // Update the total amount
            $pr->update(['estimated_total' => $totalAmount]);

            // Add approval details if status is approved or rejected
            if (in_array($status, ['approved', 'rejected'])) {
                $approver = $users->random();
                $pr->update([
                    'approved_by' => $approver->id,
                    'approved_at' => Carbon::now()->subDays(rand(1, 10)),
                    'approval_notes' => $status === 'approved' ? 'Approved as per project requirements.' : null,
                    'rejection_reason' => $status === 'rejected' ? 'Budget constraints. Please revise quantities.' : null,
                ]);
            }
        }

        $this->command->info('Purchase Requisitions seeded successfully!');
    }
}