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
        Schema::create('project_profitabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('period'); // monthly, quarterly, yearly
            $table->date('start_date');
            $table->date('end_date');
            
            // Revenue Analysis
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('contract_value', 12, 2)->default(0);
            $table->decimal('progress_billing', 12, 2)->default(0);
            $table->decimal('overrun_revenue', 12, 2)->default(0);
            
            // Cost Analysis
            $table->decimal('material_costs', 12, 2)->default(0);
            $table->decimal('labor_costs', 12, 2)->default(0);
            $table->decimal('equipment_costs', 12, 2)->default(0);
            $table->decimal('transportation_costs', 12, 2)->default(0);
            $table->decimal('permits_costs', 12, 2)->default(0);
            $table->decimal('overhead_costs', 12, 2)->default(0);
            $table->decimal('subcontractor_costs', 12, 2)->default(0);
            $table->decimal('total_costs', 12, 2)->default(0);
            
            // Profitability Metrics
            $table->decimal('gross_profit', 12, 2)->default(0);
            $table->decimal('gross_margin_percentage', 5, 2)->default(0);
            $table->decimal('net_profit', 12, 2)->default(0);
            $table->decimal('net_margin_percentage', 5, 2)->default(0);
            
            // Additional Metrics
            $table->decimal('change_order_amount', 12, 2)->default(0);
            $table->decimal('retention_amount', 12, 2)->default(0);
            $table->integer('days_completed')->default(0);
            $table->integer('total_days')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0);
            
            // Status and Notes
            $table->string('status')->default('draft'); // draft, reviewed, approved, final
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['project_id', 'period', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_profitabilities');
    }
};
