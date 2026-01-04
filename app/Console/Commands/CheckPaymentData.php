<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Commission;
use App\Models\Invoice;

class CheckPaymentData extends Command
{
    protected $signature = 'check:payment-data';
    protected $description = 'Check payment data in commissions and invoices';

    public function handle()
    {
        $this->info('Checking Commission Payment Data...');
        
        $commissions = Commission::whereNotNull('payment_details')->get();
        
        foreach ($commissions as $commission) {
            $this->info("Commission ID: {$commission->id}");
            $this->info("Payment Details: " . json_encode($commission->payment_details));
            $this->info("Paid Amount: {$commission->paid_amount}");
            $this->info("Status: {$commission->status}");
            $this->info("Payment Status: {$commission->payment_status}");
            $this->info("---");
        }
        
        $this->info('Checking Invoice Payment Data...');
        
        $invoices = Invoice::whereNotNull('payment_details')->get();
        
        foreach ($invoices as $invoice) {
            $this->info("Invoice ID: {$invoice->id}");
            $this->info("Payment Details: " . json_encode($invoice->payment_details));
            $this->info("Paid Amount: {$invoice->paid_amount}");
            $this->info("Status: {$invoice->status}");
            $this->info("---");
        }
        
        $this->info('Check completed!');
    }
}