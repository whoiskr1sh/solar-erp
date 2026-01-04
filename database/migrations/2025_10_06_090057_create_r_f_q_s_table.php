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
        Schema::create('r_f_q_s', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_number')->unique();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->date('rfq_date');
            $table->date('quotation_due_date');
            $table->date('valid_until')->nullable();
            $table->enum('status', ['draft', 'sent', 'received', 'evaluated', 'awarded', 'cancelled'])->default('draft');
            $table->text('description');
            $table->text('terms_conditions')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->text('payment_terms')->nullable();
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamps();
            
            $table->index(['project_id', 'status']);
            $table->index(['created_by', 'status']);
            $table->index(['rfq_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_f_q_s');
    }
};