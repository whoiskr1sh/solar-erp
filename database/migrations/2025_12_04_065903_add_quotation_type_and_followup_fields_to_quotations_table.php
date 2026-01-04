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
        Schema::table('quotations', function (Blueprint $table) {
            $table->enum('quotation_type', ['solar_chakki', 'solar_street_light', 'commercial', 'subsidy_quotation'])->nullable()->after('quotation_number');
            $table->timestamp('last_modified_at')->nullable()->after('updated_at');
            $table->date('follow_up_date')->nullable()->after('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['quotation_type', 'last_modified_at', 'follow_up_date']);
        });
    }
};
