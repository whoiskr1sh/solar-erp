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
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_id')->unique();
            $table->string('warehouse_name');
            $table->string('warehouse_location');
            $table->string('auditor_name');
            $table->string('auditor_designation');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('items_audited')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_audits');
    }
};