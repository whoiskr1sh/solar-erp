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
        Schema::create('r_f_q_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rfq_id')->constrained('r_f_q_s')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->integer('quantity');
            $table->string('unit')->default('pcs');
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['rfq_id', 'product_id'], 'rfq_items_rfq_product_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_f_q_items');
    }
};