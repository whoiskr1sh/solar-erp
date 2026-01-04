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
        Schema::create('material_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->onDelete('restrict');
            $table->foreignId('material_request_id')->constrained('material_requests')->onDelete('restrict');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->string('consumption_number')->unique();
            
            // Consumption Details
            $table->string('activity_type')->default('installation'); // installation, maintenance, repair, testing, demo, training
            $table->text('activity_description')->nullable();
            $table->enum('work_phase', ['preparation', 'foundation', 'structure', 'electrical', 'commissioning', 'maintenance', 'other'])->default('preparation');
            $table->string('work_location')->nullable(); // Site location, warehouse, office
            
            // Quantity Details
            $table->integer('quantity_consumed')->default(0);
            $table->string('unit_of_measurement')->nullable();
            $table->decimal('consumption_percentage', 5, 2)->default(100); // How much of the item was actually used
            $table->decimal('wastage_percentage', 5, 2)->default(0); // How much was wasted
            $table->decimal('return_percentage', 5, 2)->default(0); // How much is being returned
            
            // Quality & Status
            $table->enum('quality_status', ['good', 'damaged', 'defective', 'expired'])->default('good');
            $table->enum('consumption_status', ['draft', 'in_progress', 'completed', 'partial', 'damaged', 'returned'])->default('draft');
            $table->boolean('waste_disposed')->default(false);
            $table->boolean('return_to_stock')->default(false);
            
            // Financial & Costing
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('wastage_cost', 10, 2)->default(0);
            $table->string('cost_center')->nullable(); // Which project cost center this belongs to
            
            // Dates & Timing
            $table->date('consumption_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_hours')->nullable(); // How long did the activity take
            
            // Documentation & Approval
            $table->string('documentation_type')->nullable(); // receipt, photo, video, report
            $table->string('documentation_path')->nullable();
            $table->text('notes')->nullable();
            $table->text('quality_observations')->nullable();
            
            // User Management
            $table->foreignId('consumed_by')->constrained('users')->onDelete('restrict'); // Who consumed the material
            $table->foreignId('supervised_by')->nullable()->constrained('users')->onDelete('set null'); // Supervisor
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['consumption_date', 'consumption_status']);
            $table->index(['material_id', 'consumption_date']);
            $table->index(['project_id', 'work_phase']);
            $table->index(['consumed_by', 'consumption_date']);
            $table->index('quality_status');
            $table->index('work_phase');
            $table->index('cost_center');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_consumptions');
    }
};