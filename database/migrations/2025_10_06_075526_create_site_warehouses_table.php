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
        Schema::create('site_warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('warehouse_name');
            $table->string('location');
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->decimal('total_capacity', 10, 2)->nullable(); // in square feet or cubic meters
            $table->decimal('used_capacity', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->text('description')->nullable();
            $table->json('facilities')->nullable(); // Storage facilities, security features, etc.
            $table->foreignId('managed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['project_id', 'status']);
            $table->index(['status', 'managed_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_warehouses');
    }
};