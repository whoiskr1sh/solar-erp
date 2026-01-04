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
        Schema::table('leads', function (Blueprint $table) {
            // Add is_reassigned field
            if (!Schema::hasColumn('leads', 'is_reassigned')) {
                $table->boolean('is_reassigned')->default(false)->after('last_updated_by');
            }
        });

        // Mark existing reassigned leads (where last_updated_by != assigned_user_id and last_updated_by is not null)
        DB::table('leads')
            ->whereNotNull('last_updated_by')
            ->whereRaw('last_updated_by != assigned_user_id')
            ->update(['is_reassigned' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'is_reassigned')) {
                $table->dropColumn('is_reassigned');
            }
        });
    }
};
