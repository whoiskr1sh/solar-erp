<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commission;
use App\Models\ChannelPartner;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there's at least one user and channel partner
        $user = User::first() ?? User::factory()->create();
        $channelPartner = ChannelPartner::first();
        
        if (!$channelPartner) {
            $this->command->info('No channel partners found. Please run ChannelPartnerSeeder first.');
            return;
        }

        // Clear existing commissions to prevent duplicates on re-run
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('commissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $commissionsData = [
            [
                'channel_partner_id' => $channelPartner->id,
                'project_id' => null,
                'invoice_id' => null,
                'quotation_id' => null,
                'reference_type' => 'project',
                'reference_number' => 'PROJ-202501-0001',
                'base_amount' => 500000.00,
                'commission_rate' => 12.50,
                'commission_amount' => 62500.00,
                'paid_amount' => 0.00,
                'pending_amount' => 62500.00,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'due_date' => now()->addDays(30),
                'description' => 'Commission for Solar Installation Project - Residential Complex',
                'notes' => 'Commission based on project completion milestone.',
                'payment_details' => null,
                'documents' => [
                    'project_agreement' => 'project_agreement.pdf',
                    'commission_letter' => 'commission_letter.pdf'
                ],
                'approved_by' => null,
                'approved_at' => null,
                'created_by' => $user->id,
            ],
            [
                'channel_partner_id' => $channelPartner->id,
                'project_id' => null,
                'invoice_id' => null,
                'quotation_id' => null,
                'reference_type' => 'invoice',
                'reference_number' => 'INV-202501-0001',
                'base_amount' => 250000.00,
                'commission_rate' => 10.00,
                'commission_amount' => 25000.00,
                'paid_amount' => 25000.00,
                'pending_amount' => 0.00,
                'status' => 'paid',
                'payment_status' => 'paid',
                'due_date' => now()->subDays(15),
                'paid_date' => now()->subDays(10),
                'description' => 'Commission for Commercial Solar Invoice',
                'notes' => 'Commission paid via bank transfer.',
                'payment_details' => [
                    'method' => 'Bank Transfer',
                    'transaction_id' => 'TXN123456789',
                    'notes' => 'Payment processed successfully',
                    'paid_at' => now()->subDays(10)->toISOString(),
                    'paid_by' => $user->name
                ],
                'documents' => [
                    'invoice_copy' => 'invoice_copy.pdf',
                    'payment_receipt' => 'payment_receipt.pdf'
                ],
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(20),
                'created_by' => $user->id,
            ],
            [
                'channel_partner_id' => $channelPartner->id,
                'project_id' => null,
                'invoice_id' => null,
                'quotation_id' => null,
                'reference_type' => 'quotation',
                'reference_number' => 'QT-202501-0001',
                'base_amount' => 750000.00,
                'commission_rate' => 8.50,
                'commission_amount' => 63750.00,
                'paid_amount' => 30000.00,
                'pending_amount' => 33750.00,
                'status' => 'approved',
                'payment_status' => 'partial',
                'due_date' => now()->addDays(15),
                'description' => 'Commission for Industrial Solar Quotation',
                'notes' => 'Partial payment made, remaining amount due.',
                'payment_details' => [
                    'method' => 'Cheque',
                    'transaction_id' => 'CHQ987654321',
                    'notes' => 'First installment payment',
                    'paid_at' => now()->subDays(5)->toISOString(),
                    'paid_by' => $user->name
                ],
                'documents' => [
                    'quotation_copy' => 'quotation_copy.pdf',
                    'partial_payment_receipt' => 'partial_payment_receipt.pdf'
                ],
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(10),
                'created_by' => $user->id,
            ],
            [
                'channel_partner_id' => $channelPartner->id,
                'project_id' => null,
                'invoice_id' => null,
                'quotation_id' => null,
                'reference_type' => 'manual',
                'reference_number' => 'MAN-202501-0001',
                'base_amount' => 100000.00,
                'commission_rate' => 15.00,
                'commission_amount' => 15000.00,
                'paid_amount' => 0.00,
                'pending_amount' => 15000.00,
                'status' => 'approved',
                'payment_status' => 'unpaid',
                'due_date' => now()->subDays(5), // Overdue
                'description' => 'Manual commission entry for special project',
                'notes' => 'Special commission for exceptional performance.',
                'payment_details' => null,
                'documents' => [
                    'special_agreement' => 'special_agreement.pdf'
                ],
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(20),
                'created_by' => $user->id,
            ],
            [
                'channel_partner_id' => $channelPartner->id,
                'project_id' => null,
                'invoice_id' => null,
                'quotation_id' => null,
                'reference_type' => 'project',
                'reference_number' => 'PROJ-202501-0002',
                'base_amount' => 300000.00,
                'commission_rate' => 11.00,
                'commission_amount' => 33000.00,
                'paid_amount' => 0.00,
                'pending_amount' => 33000.00,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'due_date' => now()->addDays(45),
                'description' => 'Commission for School Solar Project',
                'notes' => 'Awaiting project completion for commission approval.',
                'payment_details' => null,
                'documents' => [
                    'project_proposal' => 'project_proposal.pdf'
                ],
                'approved_by' => null,
                'approved_at' => null,
                'created_by' => $user->id,
            ],
            [
                'channel_partner_id' => $channelPartner->id,
                'project_id' => null,
                'invoice_id' => null,
                'quotation_id' => null,
                'reference_type' => 'invoice',
                'reference_number' => 'INV-202501-0002',
                'base_amount' => 150000.00,
                'commission_rate' => 9.50,
                'commission_amount' => 14250.00,
                'paid_amount' => 0.00,
                'pending_amount' => 14250.00,
                'status' => 'disputed',
                'payment_status' => 'unpaid',
                'due_date' => now()->subDays(10), // Overdue
                'description' => 'Commission for Residential Solar Invoice',
                'notes' => 'Commission disputed due to project quality issues.',
                'payment_details' => null,
                'documents' => [
                    'dispute_letter' => 'dispute_letter.pdf',
                    'quality_report' => 'quality_report.pdf'
                ],
                'approved_by' => $user->id,
                'approved_at' => now()->subDays(5),
                'created_by' => $user->id,
            ],
        ];

        foreach ($commissionsData as $data) {
            $commission = Commission::create($data);
            // Ensure commission_number is generated and calculations are correct
            $commission->save();
        }

        $this->command->info('CommissionSeeder completed successfully!');
    }
}