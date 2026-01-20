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
            $table->dropColumn('priority');
        });
        Schema::table('todos', function (Blueprint $table) {
                if (Schema::hasTable('todos')) {
                    $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('remarks');
                }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
        Schema::table('todos', function (Blueprint $table) {
                if (Schema::hasTable('todos')) {
                    $table->integer('priority')->default(0)->after('remarks');
                }
        });
    }
};
