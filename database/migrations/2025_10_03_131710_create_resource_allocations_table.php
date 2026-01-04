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
        Schema::create('resource_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocation_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('resource_type', ['human', 'equipment', 'material', 'financial', 'other'])->default('human');
            $table->enum('status', ['planned', 'allocated', 'in_use', 'completed', 'cancelled'])->default('planned');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Project and activity information
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('activity_id')->nullable();
            
            // Resource details
            $table->string('resource_name'); // Name of the resource (person, equipment, etc.)
            $table->string('resource_category')->nullable(); // Category within type
            $table->text('resource_specifications')->nullable(); // Technical specs, skills, etc.
            
            // Allocation details
            $table->unsignedBigInteger('allocated_to')->nullable(); // User ID for human resources
            $table->unsignedBigInteger('allocated_by'); // Who made the allocation
            $table->unsignedBigInteger('approved_by')->nullable(); // Who approved the allocation
            
            // Dates and scheduling
            $table->datetime('allocation_start_date')->nullable();
            $table->datetime('allocation_end_date')->nullable();
            $table->datetime('actual_start_date')->nullable();
            $table->datetime('actual_end_date')->nullable();
            $table->datetime('approved_at')->nullable();
            
            // Quantity and capacity
            $table->decimal('allocated_quantity', 10, 2)->default(1);
            $table->decimal('actual_quantity', 10, 2)->default(0);
            $table->string('quantity_unit')->default('units'); // hours, pieces, kg, etc.
            
            // Cost and budget
            $table->decimal('hourly_rate', 10, 2)->default(0); // For human resources
            $table->decimal('unit_cost', 10, 2)->default(0); // Cost per unit
            $table->decimal('total_estimated_cost', 10, 2)->default(0);
            $table->decimal('total_actual_cost', 10, 2)->default(0);
            $table->decimal('budget_allocated', 10, 2)->default(0);
            
            // Utilization tracking
            $table->decimal('utilization_percentage', 5, 2)->default(0);
            $table->text('utilization_notes')->nullable();
            
            // Availability and constraints
            $table->json('availability_schedule')->nullable(); // Working hours, availability windows
            $table->json('constraints')->nullable(); // Skills required, location, etc.
            $table->json('dependencies')->nullable(); // Other resources this depends on
            
            // Additional fields
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_critical')->default(false);
            $table->boolean('is_billable')->default(true);
            $table->text('completion_notes')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('set null');
            $table->foreign('allocated_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('allocated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['project_id', 'status'], 'ra_project_status_idx');
            $table->index(['resource_type', 'status'], 'ra_resource_type_status_idx');
            $table->index(['allocated_to', 'status'], 'ra_allocated_to_status_idx');
            $table->index(['allocation_start_date', 'allocation_end_date'], 'ra_dates_idx');
            $table->index(['status', 'priority'], 'ra_status_priority_idx');
            $table->index(['is_critical', 'status'], 'ra_critical_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_allocations');
    }
};
