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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_code')->unique();
            $table->foreignId('material_request_id')->constrained('material_requests')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('specification')->nullable();
            $table->string('unit')->nullable(); // piece, kg, meter, liter, etc.
            $table->integer('quantity')->default(0);
            $table->integer('approved_quantity')->default(0);
            $table->integer('received_quantity')->default(0);
            $table->integer('consumed_quantity')->default(0);
            $table->integer('remaining_quantity')->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', ['requested', 'approved', 'ordered', 'received', 'consumed', 'returned'])->default('requested');
            $table->enum('quality', ['excellent', 'good', 'average', 'poor'])->nullable();
            $table->string('supplier')->nullable();
            $table->string('brand')->nullable();
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('notes')->nullable();
            $table->json('technical_specs')->nullable();
            $table->timestamps();

            $table->index('material_request_id');
            $table->index('status');
            $table->index('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};