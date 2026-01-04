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
            if (!Schema::hasColumn('leads', 'consumer_number')) {
                $table->string('consumer_number', 100)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('leads', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('leads', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('leads', 'pincode')) {
                $table->string('pincode', 10)->nullable()->after('state');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'consumer_number')) {
                $table->dropColumn('consumer_number');
            }
            if (Schema::hasColumn('leads', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('leads', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('leads', 'pincode')) {
                $table->dropColumn('pincode');
            }
        });
    }
};
