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
        Schema::create('payment_milestones', function (Blueprint $table) {
            $table->id();
            $table->string('milestone_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('quotation_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('milestone_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->enum('milestone_type', ['advance', 'progress', 'completion', 'retention', 'final']);
            $table->integer('milestone_percentage')->default(0)->comment('Percentage of work completed');
            $table->date('planned_date');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'overdue', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'online', 'upi', 'card'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('payment_notes')->nullable();
            $table->text('milestone_notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('attachments')->nullable()->comment('Payment receipts or documents');
            $table->timestamps();
            
            $table->index(['project_id', 'status']);
            $table->index(['milestone_type', 'status']);
            $table->index(['due_date', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_milestones');
    }
};