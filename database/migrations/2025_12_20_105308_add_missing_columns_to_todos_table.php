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
        Schema::table('todos', function (Blueprint $table) {
            if (Schema::hasTable('todos')) {
                if (!Schema::hasColumn('todos', 'is_daily_task')) {
                    $table->boolean('is_daily_task')->default(true)->after('incomplete_reason');
                }
                if (!Schema::hasColumn('todos', 'completion_date')) {
                    $table->date('completion_date')->nullable()->after('completed_at');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            if (Schema::hasTable('todos')) {
                if (Schema::hasColumn('todos', 'is_daily_task')) {
                    $table->dropColumn('is_daily_task');
                }
                if (Schema::hasColumn('todos', 'completion_date')) {
                    $table->dropColumn('completion_date');
                }
            }
        });
    }
};
