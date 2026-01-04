<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\User;

class PaymentTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user for paid_by field
        $user = User::first();
        if (!$user) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        // Update commissions with payment details
        $commissions = Commission::take(3)->get();
        foreach ($commissions as $index => $commission) {
            $paymentMethods = ['Bank Transfer', 'UPI', 'Cheque'];
            $transactionIds = ['TXN123456789', 'UPI987654321', 'CHQ456789123'];
            
            $commission->update([
                'payment_details' => [
                    'method' => $paymentMethods[$index % 3],
                    'transaction_id' => $transactionIds[$index % 3],
                    'paid_by' => $user->name,
                    'paid_at' => now()->subDays($index + 1)->toISOString(),
                    'notes' => 'Payment received via ' . $paymentMethods[$index % 3]
                ],
                'paid_amount' => $commission->commission_amount * 0.8, // 80% paid
                'status' => 'paid',
                'payment_status' => 'paid',
                'paid_date' => now()->subDays($index + 1)
            ]);
        }

        // Update invoices with payment details
        $invoices = Invoice::take(2)->get();
        foreach ($invoices as $index => $invoice) {
            $paymentMethods = ['Bank Transfer', 'Card'];
            $transactionIds = ['INV123456789', 'CARD987654321'];
            
            $invoice->update([
                'payment_details' => [
                    'method' => $paymentMethods[$index % 2],
                    'transaction_id' => $transactionIds[$index % 2],
                    'paid_by' => $user->name,
                    'paid_at' => now()->subDays($index + 2)->toISOString(),
                    'notes' => 'Invoice payment via ' . $paymentMethods[$index % 2]
                ],
                'paid_amount' => $invoice->total_amount * 0.9, // 90% paid
                'status' => 'paid',
                'paid_date' => now()->subDays($index + 2)
            ]);
        }

        $this->command->info('Payment test data seeded successfully!');
    }
}