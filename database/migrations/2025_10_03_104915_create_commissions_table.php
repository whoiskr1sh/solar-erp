<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->string('commission_number')->unique();
            $table->foreignId('channel_partner_id')->constrained('channel_partners')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->onDelete('set null');
            $table->string('reference_type')->nullable(); // 'project', 'invoice', 'quotation', 'manual'
            $table->string('reference_number')->nullable(); // Reference number for the source
            $table->decimal('base_amount', 15, 2)->default(0); // Amount on which commission is calculated
            $table->decimal('commission_rate', 5, 2)->default(0); // Commission percentage
            $table->decimal('commission_amount', 15, 2)->default(0); // Calculated commission amount
            $table->decimal('paid_amount', 15, 2)->default(0); // Amount already paid
            $table->decimal('pending_amount', 15, 2)->default(0); // Amount pending to be paid
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled', 'disputed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->date('due_date')->nullable(); // When commission should be paid
            $table->date('paid_date')->nullable(); // When commission was actually paid
            $table->text('description')->nullable(); // Description of the commission
            $table->text('notes')->nullable(); // Additional notes
            $table->json('payment_details')->nullable(); // Payment method, transaction details
            $table->json('documents')->nullable(); // Supporting documents
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};