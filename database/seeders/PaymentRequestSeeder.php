<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentRequest;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class PaymentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = Vendor::all();
        $projects = Project::all();
        $users = User::all();

        if ($vendors->isEmpty() || $projects->isEmpty() || $users->isEmpty()) {
            $this->command->info('Skipping PaymentRequestSeeder: No vendors, projects, or users found. Please run respective seeders first.');
            return;
        }

        $statuses = ['draft', 'submitted', 'approved', 'rejected', 'paid', 'cancelled'];
        $paymentTypes = ['advance', 'milestone', 'final', 'retention', 'other'];
        $descriptions = [
            'Payment for solar panel installation materials',
            'Milestone payment for inverter setup',
            'Final payment for project completion',
            'Advance payment for equipment procurement',
            'Retention payment for warranty period',
            'Payment for maintenance services',
            'Payment for additional materials',
            'Payment for labor charges',
        ];

        for ($i = 0; $i < 10; $i++) {
            $vendor = $vendors->random();
            $project = $projects->random();
            $requester = $users->random();
            $status = $statuses[array_rand($statuses)];
            $paymentType = $paymentTypes[array_rand($paymentTypes)];
            $description = $descriptions[array_rand($descriptions)];
            
            $requestDate = Carbon::now()->subDays(rand(1, 30));
            $dueDate = $requestDate->copy()->addDays(rand(7, 45));
            $amount = rand(50000, 500000) / 100; // Amount between 500 and 5000

            $pr = PaymentRequest::create([
                'pr_number' => 'PR-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'vendor_id' => $vendor->id,
                'project_id' => $project->id,
                'request_date' => $requestDate,
                'due_date' => $dueDate,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'status' => $status,
                'description' => $description,
                'justification' => 'Required for ' . $project->name . ' project completion.',
                'invoice_number' => (rand(0, 1) ? 'INV-' . rand(1000, 9999) : null),
                'invoice_date' => (rand(0, 1) ? $requestDate->copy()->addDays(rand(1, 10)) : null),
                'invoice_amount' => (rand(0, 1) ? $amount : null),
                'requested_by' => $requester->id,
            ]);

            // Add approval details if status is approved or rejected
            if (in_array($status, ['approved', 'rejected', 'paid'])) {
                $approver = $users->random();
                $pr->update([
                    'approved_by' => $approver->id,
                    'approved_at' => Carbon::now()->subDays(rand(1, 10)),
                    'approval_notes' => $status === 'approved' ? 'Approved as per project requirements.' : null,
                    'rejection_reason' => $status === 'rejected' ? 'Insufficient documentation provided.' : null,
                ]);
            }
        }

        $this->command->info('Payment Requests seeded successfully!');
    }
}