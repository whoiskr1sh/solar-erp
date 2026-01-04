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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->string('advance_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null'); // For employee advances
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null'); // For vendor advances
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('advance_type', ['employee', 'vendor', 'project'])->default('employee');
            $table->decimal('amount', 15, 2);
            $table->date('advance_date');
            $table->date('expected_settlement_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'settled', 'partially_settled'])->default('pending');
            $table->decimal('settled_amount', 15, 2)->default(0);
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'status']);
            $table->index(['vendor_id', 'status']);
            $table->index(['project_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
