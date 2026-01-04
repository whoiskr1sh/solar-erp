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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->time('transaction_time');
            $table->string('item_name');
            $table->string('item_code');
            $table->enum('transaction_type', ['inward', 'outward', 'transfer']);
            $table->string('reference_number');
            $table->integer('inward_quantity')->default(0);
            $table->integer('outward_quantity')->default(0);
            $table->integer('balance_quantity');
            $table->string('warehouse');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};