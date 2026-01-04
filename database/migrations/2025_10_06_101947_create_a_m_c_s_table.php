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
        Schema::create('a_m_c_s', function (Blueprint $table) {
            $table->id();
            $table->string('amc_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('project_name');
            $table->string('project_location');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('contract_value', 15, 2);
            $table->enum('status', ['active', 'expired', 'renewed', 'cancelled']);
            $table->text('services_included');
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_m_c_s');
    }
};