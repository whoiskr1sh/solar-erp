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
            // First, change status to VARCHAR temporarily to allow any value
            \DB::statement("ALTER TABLE leads MODIFY COLUMN status VARCHAR(50) DEFAULT 'interested'");
        });

        // Map existing statuses to new ones
        \DB::table('leads')->where('status', 'new')->update(['status' => 'interested']);
        \DB::table('leads')->where('status', 'contacted')->update(['status' => 'interested']);
        \DB::table('leads')->where('status', 'qualified')->update(['status' => 'interested']);
        \DB::table('leads')->where('status', 'proposal')->update(['status' => 'partially_interested']);
        \DB::table('leads')->where('status', 'negotiation')->update(['status' => 'partially_interested']);
        \DB::table('leads')->where('status', 'converted')->update(['status' => 'interested']);
        \DB::table('leads')->where('status', 'lost')->update(['status' => 'not_interested']);

        Schema::table('leads', function (Blueprint $table) {
            // Now change back to enum with new values
            \DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('interested', 'not_interested', 'partially_interested', 'not_reachable', 'not_answered') DEFAULT 'interested'");
            
            // Add follow-up tracking fields
            $table->date('follow_up_date')->nullable()->after('expected_close_date');
            $table->text('follow_up_notes')->nullable()->after('follow_up_date');
            $table->timestamp('last_follow_up_at')->nullable()->after('follow_up_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Revert to old status enum
            \DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('new', 'contacted', 'qualified', 'proposal', 'negotiation', 'converted', 'lost') DEFAULT 'new'");
            
            $table->dropColumn(['follow_up_date', 'follow_up_notes', 'last_follow_up_at']);
        });
    }
};
