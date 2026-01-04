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
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->foreignId('audited_by')->nullable()->constrained('users')->onDelete('set null')->after('auditor_designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_audits', function (Blueprint $table) {
            $table->dropForeign(['audited_by']);
            $table->dropColumn('audited_by');
        });
    }
};
