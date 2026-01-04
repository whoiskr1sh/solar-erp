<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing invoices to prevent duplicates
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('invoices')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $users = User::all();
        $clients = Lead::all(); // Use all leads for now
        $projects = Project::all(); // Use all projects for now
        
        $invoices = [
            [
                'invoice_number' => 'INV-2024-007',
                'invoice_date' => now()->subDays(30),
                'due_date' => now()->subDays(15),
                'client_id' => $clients->first()->id ?? null,
                'project_id' => $projects->first()->id ?? null,
                'subtotal' => 750000,
                'tax_amount' => 135000,
                'total_amount' => 885000,
                'paid_amount' => 885000,
                'status' => 'paid',
                'notes' => 'Payment received in full. Thank you for choosing Solar ERP!',
                'terms_conditions' => 'Payment due within 15 days. Late payment charges may apply.',
                'created_by' => $users->first()->id,
            ],
            [
                'invoice_number' => 'INV-2024-008',
                'invoice_date' => now()->subDays(20),
                'due_date' => now()->addDays(5),
                'client_id' => $clients->skip(1)->first()->id ?? null,
                'project_id' => $projects->skip(1)->first()->id ?? null,
                'subtotal' => 250000,
                'tax_amount' => 45000,
                'total_amount' => 295000,
                'paid_amount' => 0,
                'status' => 'sent',
                'notes' => 'Residential solar installation invoice. Please process payment at your earliest convenience.',
                'terms_conditions' => 'Payment due within 25 days. Early payment discount available.',
                'created_by' => $users->first()->id,
            ],
            [
                'invoice_number' => 'INV-2024-009',
                'invoice_date' => now()->subDays(45),
                'due_date' => now()->subDays(20),
                'client_id' => $clients->skip(2)->first()->id ?? null,
                'project_id' => $projects->skip(2)->first()->id ?? null,
                'subtotal' => 2000000,
                'tax_amount' => 360000,
                'total_amount' => 2360000,
                'paid_amount' => 1000000,
                'status' => 'sent',
                'notes' => 'Industrial solar plant installation - Phase 1 completed. Partial payment received.',
                'terms_conditions' => 'Payment in installments as per project milestones. Interest charges apply on overdue amounts.',
                'created_by' => $users->first()->id,
            ],
            [
                'invoice_number' => 'INV-2024-010',
                'invoice_date' => now()->subDays(60),
                'due_date' => now()->subDays(35),
                'client_id' => $clients->skip(3)->first()->id ?? null,
                'project_id' => $projects->skip(3)->first()->id ?? null,
                'subtotal' => 800000,
                'tax_amount' => 144000,
                'total_amount' => 944000,
                'paid_amount' => 944000,
                'status' => 'paid',
                'notes' => 'Hospital solar backup system installation completed successfully.',
                'terms_conditions' => 'Payment due within 25 days. Warranty coverage as per contract.',
                'created_by' => $users->first()->id,
            ],
            [
                'invoice_number' => 'INV-2024-011',
                'invoice_date' => now()->subDays(10),
                'due_date' => now()->addDays(15),
                'client_id' => $clients->first()->id ?? null,
                'project_id' => null,
                'subtotal' => 50000,
                'tax_amount' => 9000,
                'total_amount' => 59000,
                'paid_amount' => 0,
                'status' => 'draft',
                'notes' => 'Maintenance service invoice for existing solar installation.',
                'terms_conditions' => 'Payment due within 25 days. Service warranty valid for 6 months.',
                'created_by' => $users->first()->id,
            ],
            [
                'invoice_number' => 'INV-2024-012',
                'invoice_date' => now()->subDays(90),
                'due_date' => now()->subDays(65),
                'client_id' => $clients->skip(1)->first()->id ?? null,
                'project_id' => null,
                'subtotal' => 120000,
                'tax_amount' => 21600,
                'total_amount' => 141600,
                'paid_amount' => 0,
                'status' => 'overdue',
                'notes' => 'Overdue invoice for solar panel cleaning and maintenance services.',
                'terms_conditions' => 'Payment overdue. Please contact us immediately to resolve payment.',
                'created_by' => $users->first()->id,
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Invoice::create($invoiceData);
        }
    }
}
