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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number')->unique(); // Payment Request Number
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('set null');
            $table->date('request_date');
            $table->date('due_date');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_type', ['advance', 'milestone', 'final', 'retention', 'other'])->default('milestone');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'paid', 'cancelled'])->default('draft');
            $table->text('description');
            $table->text('justification')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('invoice_amount', 15, 2)->nullable();
            $table->text('supporting_documents')->nullable(); // JSON array of file paths
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->index(['vendor_id', 'status']);
            $table->index(['project_id', 'status']);
            $table->index(['requested_by', 'status']);
            $table->index(['request_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};