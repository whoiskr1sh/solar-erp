<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;

class FixInvoicePaymentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fix invoices with proper array format
        $invoices = Invoice::whereNotNull('payment_details')->get();
        foreach ($invoices as $invoice) {
            if (!is_array($invoice->payment_details)) {
                $paymentDetails = [$invoice->payment_details];
                $invoice->update(['payment_details' => $paymentDetails]);
                $this->command->info("Fixed Invoice ID: {$invoice->id}");
            }
        }

        $this->command->info('Invoice payment data fixed successfully!');
    }
}
