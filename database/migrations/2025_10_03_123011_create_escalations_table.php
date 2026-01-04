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
        Schema::create('escalations', function (Blueprint $table) {
            $table->id();
            $table->string('escalation_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['complaint', 'issue', 'request', 'incident', 'other'])->default('complaint');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'cancelled'])->default('open');
            $table->enum('category', ['technical', 'billing', 'service', 'support', 'general'])->default('general');
            
            // Related entities
            $table->unsignedBigInteger('related_lead_id')->nullable();
            $table->unsignedBigInteger('related_project_id')->nullable();
            $table->unsignedBigInteger('related_invoice_id')->nullable();
            $table->unsignedBigInteger('related_quotation_id')->nullable();
            $table->unsignedBigInteger('related_commission_id')->nullable();
            
            // Assignment
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('escalated_to')->nullable();
            $table->unsignedBigInteger('created_by');
            
            // Customer information
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Dates
            $table->datetime('due_date')->nullable();
            $table->datetime('resolved_at')->nullable();
            $table->datetime('closed_at')->nullable();
            
            // Additional fields
            $table->text('resolution_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->json('attachments')->nullable();
            $table->json('tags')->nullable();
            $table->integer('escalation_level')->default(1);
            $table->boolean('is_urgent')->default(false);
            $table->boolean('requires_response')->default(true);
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('related_lead_id')->references('id')->on('leads')->onDelete('set null');
            $table->foreign('related_project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('related_invoice_id')->references('id')->on('invoices')->onDelete('set null');
            $table->foreign('related_quotation_id')->references('id')->on('quotations')->onDelete('set null');
            $table->foreign('related_commission_id')->references('id')->on('commissions')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('escalated_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);
            $table->index(['created_at', 'status']);
            $table->index(['due_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalations');
    }
};
