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
        // Add method field if it doesn't exist
        if (!Schema::hasColumn('lead_reassignment_requests', 'method')) {
            Schema::table('lead_reassignment_requests', function (Blueprint $table) {
                $table->enum('method', ['count', 'specific'])->nullable()->after('reason');
            });
        }
        
        // Add count column if it doesn't exist
        if (!Schema::hasColumn('lead_reassignment_requests', 'count')) {
            Schema::table('lead_reassignment_requests', function (Blueprint $table) {
                $table->integer('count')->nullable()->after('method');
            });
            
            // Copy data from leads_count to count if leads_count exists
            if (Schema::hasColumn('lead_reassignment_requests', 'leads_count')) {
                DB::statement('UPDATE lead_reassignment_requests SET `count` = `leads_count` WHERE `count` IS NULL');
            }
        }
        
        // Update status from multi-level to simple pending
        DB::table('lead_reassignment_requests')
            ->whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])
            ->update(['status' => 'pending']);
        
        // Update method based on existing data
        DB::table('lead_reassignment_requests')
            ->whereNull('method')
            ->where(function($q) {
                $q->whereNotNull('selected_lead_ids')
                  ->where('selected_lead_ids', '!=', '[]')
                  ->where('selected_lead_ids', '!=', 'null');
            })
            ->update(['method' => 'specific']);
        
        DB::table('lead_reassignment_requests')
            ->whereNull('method')
            ->whereNotNull('count')
            ->update(['method' => 'count']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop method and count columns
        if (Schema::hasColumn('lead_reassignment_requests', 'method')) {
            Schema::table('lead_reassignment_requests', function (Blueprint $table) {
                $table->dropColumn('method');
            });
        }
        
        if (Schema::hasColumn('lead_reassignment_requests', 'count')) {
            Schema::table('lead_reassignment_requests', function (Blueprint $table) {
                $table->dropColumn('count');
            });
        }
        
        // Revert status back to multi-level
        DB::table('lead_reassignment_requests')
            ->where('status', 'pending')
            ->update(['status' => 'pending_manager_approval']);
    }
};
