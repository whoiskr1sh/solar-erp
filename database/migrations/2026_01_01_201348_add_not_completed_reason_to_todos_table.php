<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
            if (!Schema::hasColumn('todos', 'not_completed_reason')) {
                $table->text('not_completed_reason')->nullable()->after('remarks');
            }
        });
    }
    
    public function down()
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('not_completed_reason');
        });
    }
    
};
