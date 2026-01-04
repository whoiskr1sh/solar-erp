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
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            // $table->foreignId('site_id')->nullable()->constrained('sites')->onDelete('set null');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'pending', 'approved', 'in_progress', 'partial', 'completed', 'rejected', 'cancelled'])->default('draft');
            $table->enum('category', ['raw_materials', 'tools_equipment', 'consumables', 'safety_items', 'electrical', 'mechanical', 'other'])->default('raw_materials');
            $table->enum('request_type', ['purchase', 'rental', 'transfer', 'emergency'])->default('purchase');
            $table->date('required_date');
            $table->date('approved_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('approved_amount', 12, 2)->default(0);
            $table->decimal('consumed_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->enum('urgency_reason', ['normal', 'delay_risk', 'deadline_critical', 'equipment_failure', 'weather_dependent'])->default('normal');
            $table->text('justification')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('specifications')->nullable();
            $table->json('attachments')->nullable();
            $table->foreignId('requested_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->integer('days_until_required')->default(0);
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index(['project_id', 'status']);
            $table->index(['requested_by', 'status']);
            $table->index(['required_date', 'status']);
            $table->index('category');
            $table->index('request_type');
            $table->index(['is_urgent', 'required_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};