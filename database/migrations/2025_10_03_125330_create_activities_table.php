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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['task', 'milestone', 'meeting', 'delivery', 'review', 'other'])->default('task');
            $table->enum('status', ['planned', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('planned');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Project and phase information
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('phase_id')->nullable();
            
            // Assignment and tracking
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            
            // Dates and scheduling
            $table->datetime('planned_start_date')->nullable();
            $table->datetime('planned_end_date')->nullable();
            $table->datetime('actual_start_date')->nullable();
            $table->datetime('actual_end_date')->nullable();
            $table->datetime('approved_at')->nullable();
            
            // Progress and effort tracking
            $table->integer('estimated_hours')->default(0);
            $table->integer('actual_hours')->default(0);
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->text('progress_notes')->nullable();
            
            // Dependencies and relationships
            $table->json('dependencies')->nullable(); // Array of activity IDs this depends on
            $table->json('blockers')->nullable(); // Array of blocking issues
            $table->json('resources')->nullable(); // Required resources
            
            // Deliverables and outcomes
            $table->text('deliverables')->nullable();
            $table->text('acceptance_criteria')->nullable();
            $table->text('completion_notes')->nullable();
            
            // Additional fields
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_milestone')->default(false);
            $table->boolean('is_billable')->default(true);
            $table->decimal('estimated_cost', 10, 2)->default(0);
            $table->decimal('actual_cost', 10, 2)->default(0);
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['project_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['planned_start_date', 'planned_end_date']);
            $table->index(['status', 'priority']);
            $table->index(['is_milestone', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
