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
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignId('parent_quotation_id')->nullable()->after('id')->constrained('quotations')->onDelete('cascade');
            $table->integer('revision_number')->default(0)->after('parent_quotation_id');
            $table->boolean('is_revision')->default(false)->after('revision_number');
            $table->boolean('is_latest')->default(true)->after('is_revision');
            
            $table->index(['parent_quotation_id', 'revision_number']);
            $table->index('is_latest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['parent_quotation_id']);
            $table->dropIndex(['parent_quotation_id', 'revision_number']);
            $table->dropIndex(['is_latest']);
            $table->dropColumn(['parent_quotation_id', 'revision_number', 'is_revision', 'is_latest']);
        });
    }
};
