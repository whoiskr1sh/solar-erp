<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('selected_revised_quotation_id')->nullable()->after('is_reassigned');
            $table->foreign('selected_revised_quotation_id')->references('id')->on('quotations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['selected_revised_quotation_id']);
            $table->dropColumn('selected_revised_quotation_id');
        });
    }
};
