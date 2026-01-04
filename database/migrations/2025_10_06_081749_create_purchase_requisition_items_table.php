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
        Schema::create('purchase_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_requisition_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->integer('quantity');
            $table->decimal('estimated_unit_price', 10, 2)->nullable();
            $table->decimal('estimated_total_price', 15, 2)->nullable();
            $table->string('unit')->default('pcs');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['purchase_requisition_id', 'product_id'], 'pr_items_pr_product_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisition_items');
    }
};