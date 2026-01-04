<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Commission;
use App\Models\Invoice;

class ConvertPaymentData extends Command
{
    protected $signature = 'convert:payment-data';
    protected $description = 'Convert existing payment data to array format';

    public function handle()
    {
        $this->info('Converting Commission Payment Data...');
        
        $commissions = Commission::whereNotNull('payment_details')->get();
        
        foreach ($commissions as $commission) {
            if (!is_array($commission->payment_details)) {
                $paymentDetails = [$commission->payment_details];
                $commission->update(['payment_details' => $paymentDetails]);
                $this->info("Converted Commission ID: {$commission->id}");
            }
        }
        
        $this->info('Converting Invoice Payment Data...');
        
        $invoices = Invoice::whereNotNull('payment_details')->get();
        
        foreach ($invoices as $invoice) {
            if (!is_array($invoice->payment_details)) {
                $paymentDetails = [$invoice->payment_details];
                $invoice->update(['payment_details' => $paymentDetails]);
                $this->info("Converted Invoice ID: {$invoice->id}");
            }
        }
        
        $this->info('Conversion completed!');
    }
}