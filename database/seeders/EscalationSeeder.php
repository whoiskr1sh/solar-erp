<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Escalation;
use App\Models\User;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Commission;

class EscalationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $leads = Lead::all();
        $projects = Project::all();
        $invoices = Invoice::all();
        $quotations = Quotation::all();
        $commissions = Commission::all();

        $escalations = [
            [
                'title' => 'Solar Panel Installation Delay',
                'description' => 'Customer is complaining about the delay in solar panel installation. The project was supposed to be completed last week but is still ongoing.',
                'type' => 'complaint',
                'priority' => 'high',
                'category' => 'service',
                'customer_name' => 'Rajesh Kumar',
                'customer_email' => 'rajesh.kumar@email.com',
                'customer_phone' => '+91 9876543210',
                'due_date' => now()->addDays(2),
                'internal_notes' => 'Customer is VIP client, needs immediate attention.',
                'tags' => ['installation', 'delay', 'vip'],
                'is_urgent' => true,
                'requires_response' => true,
            ],
            [
                'title' => 'Billing Discrepancy',
                'description' => 'Customer received an invoice with incorrect amount. The billing team needs to review and correct the invoice.',
                'type' => 'issue',
                'priority' => 'medium',
                'category' => 'billing',
                'customer_name' => 'Priya Sharma',
                'customer_email' => 'priya.sharma@email.com',
                'customer_phone' => '+91 9876543211',
                'due_date' => now()->addDays(1),
                'internal_notes' => 'Check invoice calculation and update customer.',
                'tags' => ['billing', 'invoice', 'calculation'],
                'is_urgent' => false,
                'requires_response' => true,
            ],
            [
                'title' => 'Technical Support Request',
                'description' => 'Customer needs technical support for their solar inverter. The inverter is not working properly after installation.',
                'type' => 'request',
                'priority' => 'high',
                'category' => 'technical',
                'customer_name' => 'Amit Patel',
                'customer_email' => 'amit.patel@email.com',
                'customer_phone' => '+91 9876543212',
                'due_date' => now()->addDays(1),
                'internal_notes' => 'Send technical team to customer site.',
                'tags' => ['technical', 'inverter', 'support'],
                'is_urgent' => true,
                'requires_response' => true,
            ],
            [
                'title' => 'Commission Payment Issue',
                'description' => 'Channel partner is complaining about delayed commission payment. Payment was due last month.',
                'type' => 'complaint',
                'priority' => 'medium',
                'category' => 'billing',
                'customer_name' => 'Sunil Gupta',
                'customer_email' => 'sunil.gupta@email.com',
                'customer_phone' => '+91 9876543213',
                'due_date' => now()->addDays(3),
                'internal_notes' => 'Check commission calculation and process payment.',
                'tags' => ['commission', 'payment', 'channel-partner'],
                'is_urgent' => false,
                'requires_response' => true,
            ],
            [
                'title' => 'Project Timeline Extension',
                'description' => 'Customer is requesting extension of project timeline due to weather conditions.',
                'type' => 'request',
                'priority' => 'low',
                'category' => 'service',
                'customer_name' => 'Neha Singh',
                'customer_email' => 'neha.singh@email.com',
                'customer_phone' => '+91 9876543214',
                'due_date' => now()->addDays(5),
                'internal_notes' => 'Review project timeline and update customer.',
                'tags' => ['timeline', 'extension', 'weather'],
                'is_urgent' => false,
                'requires_response' => true,
            ],
            [
                'title' => 'System Performance Issue',
                'description' => 'Solar system is not generating expected power output. Customer is concerned about ROI.',
                'type' => 'incident',
                'priority' => 'critical',
                'category' => 'technical',
                'customer_name' => 'Vikram Mehta',
                'customer_email' => 'vikram.mehta@email.com',
                'customer_phone' => '+91 9876543215',
                'due_date' => now()->addDays(1),
                'internal_notes' => 'Critical issue - send senior technician immediately.',
                'tags' => ['performance', 'power-output', 'roi'],
                'is_urgent' => true,
                'requires_response' => true,
            ],
        ];

        foreach ($escalations as $index => $escalationData) {
            $escalation = Escalation::create([
                'escalation_number' => Escalation::generateEscalationNumber(),
                'title' => $escalationData['title'],
                'description' => $escalationData['description'],
                'type' => $escalationData['type'],
                'priority' => $escalationData['priority'],
                'category' => $escalationData['category'],
                'status' => $index < 2 ? 'open' : ($index < 4 ? 'in_progress' : 'resolved'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'customer_name' => $escalationData['customer_name'],
                'customer_email' => $escalationData['customer_email'],
                'customer_phone' => $escalationData['customer_phone'],
                'due_date' => $escalationData['due_date'],
                'related_lead_id' => $leads->isNotEmpty() ? $leads->random()->id : null,
                'related_project_id' => $projects->isNotEmpty() ? $projects->random()->id : null,
                'related_invoice_id' => $invoices->isNotEmpty() ? $invoices->random()->id : null,
                'related_quotation_id' => $quotations->isNotEmpty() ? $quotations->random()->id : null,
                'related_commission_id' => $commissions->isNotEmpty() ? $commissions->random()->id : null,
                'internal_notes' => $escalationData['internal_notes'],
                'tags' => $escalationData['tags'],
                'is_urgent' => $escalationData['is_urgent'],
                'requires_response' => $escalationData['requires_response'],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Set resolved_at for resolved escalations
            if ($escalation->status === 'resolved') {
                $escalation->update([
                    'resolved_at' => now()->subDays(rand(1, 10)),
                    'resolution_notes' => 'Issue has been resolved successfully. Customer is satisfied with the solution provided.',
                ]);
            }
        }
    }
}