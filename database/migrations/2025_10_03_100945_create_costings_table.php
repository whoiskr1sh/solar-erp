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
        Schema::create('costings', function (Blueprint $table) {
            $table->id();
            $table->string('costing_number')->unique();
            $table->string('project_name');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('project_description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('total_cost', 15, 2);
            $table->decimal('material_cost', 15, 2)->default(0);
            $table->decimal('labor_cost', 15, 2)->default(0);
            $table->decimal('equipment_cost', 15, 2)->default(0);
            $table->decimal('transportation_cost', 15, 2)->default(0);
            $table->decimal('overhead_cost', 15, 2)->default(0);
            $table->decimal('profit_margin', 5, 2)->default(0); // Percentage
            $table->decimal('tax_rate', 5, 2)->default(0); // Percentage
            $table->decimal('discount', 15, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->date('validity_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('cost_breakdown')->nullable(); // Detailed breakdown
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['status', 'created_at']);
            $table->index(['project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costings');
    }
};