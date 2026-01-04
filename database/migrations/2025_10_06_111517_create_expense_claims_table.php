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
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('claim_number')->unique();
            $table->string('expense_type');
            $table->date('expense_date');
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->string('receipt_path')->nullable();
            $table->enum('status', ['submitted', 'approved', 'rejected', 'paid']);
            $table->string('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_claims');
    }
};