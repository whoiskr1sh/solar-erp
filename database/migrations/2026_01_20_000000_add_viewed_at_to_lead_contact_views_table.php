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
        if (Schema::hasTable('lead_contact_views') && !Schema::hasColumn('lead_contact_views', 'viewed_at')) {
            Schema::table('lead_contact_views', function (Blueprint $table) {
                $table->timestamp('viewed_at')->nullable()->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lead_contact_views') && Schema::hasColumn('lead_contact_views', 'viewed_at')) {
            Schema::table('lead_contact_views', function (Blueprint $table) {
                $table->dropColumn('viewed_at');
            });
        }
    }
};
