<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set default quotation_type for existing quotations that don't have a type
        DB::table('quotations')
            ->whereNull('quotation_type')
            ->update(['quotation_type' => 'commercial']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally set quotation_type back to null (though this might cause issues)
        // DB::table('quotations')
        //     ->where('quotation_type', 'commercial')
        //     ->update(['quotation_type' => null]);
    }
};
