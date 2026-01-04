<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\User;

class FixPaymentDataSeeder extends Seeder
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

        // Fix commissions with proper array format
        $commissions = Commission::whereNotNull('payment_details')->get();
        foreach ($commissions as $commission) {
            if (!is_array($commission->payment_details)) {
                $paymentDetails = [$commission->payment_details];
                $commission->update(['payment_details' => $paymentDetails]);
                $this->command->info("Fixed Commission ID: {$commission->id}");
            }
        }

        // Fix invoices with proper array format
        $invoices = Invoice::whereNotNull('payment_details')->get();
        foreach ($invoices as $invoice) {
            if (!is_array($invoice->payment_details)) {
                $paymentDetails = [$invoice->payment_details];
                $invoice->update(['payment_details' => $paymentDetails]);
                $this->command->info("Fixed Invoice ID: {$invoice->id}");
            }
        }

        // Add new test data in proper array format
        $commissions = Commission::take(2)->get();
        foreach ($commissions as $index => $commission) {
            $paymentMethods = ['UPI', 'Cheque'];
            $transactionIds = ['UPI987654321', 'CHQ456789123'];
            
            $paymentDetails = [
                [
                    'method' => $paymentMethods[$index % 2],
                    'transaction_id' => $transactionIds[$index % 2],
                    'paid_by' => $user->name,
                    'paid_at' => now()->subDays($index + 3)->toISOString(),
                    'notes' => 'Payment received via ' . $paymentMethods[$index % 2]
                ]
            ];
            
            $commission->update([
                'payment_details' => $paymentDetails,
                'paid_amount' => $commission->commission_amount * 0.9,
                'status' => 'paid',
                'payment_status' => 'paid',
                'paid_date' => now()->subDays($index + 3)
            ]);
        }

        $this->command->info('Payment data fixed and new test data added successfully!');
    }
}
