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
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'electricity_bill_path')) {
                $table->string('electricity_bill_path')->nullable()->after('last_follow_up_at');
            }
            if (!Schema::hasColumn('leads', 'cancelled_cheque_path')) {
                $table->string('cancelled_cheque_path')->nullable()->after('electricity_bill_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'electricity_bill_path')) {
                $table->dropColumn('electricity_bill_path');
            }
            if (Schema::hasColumn('leads', 'cancelled_cheque_path')) {
                $table->dropColumn('cancelled_cheque_path');
            }
        });
    }
};
