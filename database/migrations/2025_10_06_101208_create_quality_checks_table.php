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
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->string('qc_number')->unique();
            $table->string('item_name');
            $table->string('item_code');
            $table->string('vendor_name');
            $table->string('inspector_name');
            $table->string('inspector_designation');
            $table->enum('status', ['pending', 'passed', 'failed', 'rejected']);
            $table->date('qc_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};