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
        // Update existing pending requests to pending_manager_approval
        DB::table('lead_reassignment_requests')
            ->where('status', 'pending')
            ->update(['status' => 'pending_manager_approval']);
        
        Schema::table('lead_reassignment_requests', function (Blueprint $table) {
            // Drop the old status column
            $table->dropColumn('status');
        });
        
        Schema::table('lead_reassignment_requests', function (Blueprint $table) {
            // Add new status enum with multi-level approval
            $table->enum('status', ['pending_manager_approval', 'pending_admin_approval', 'approved', 'rejected'])->default('pending_manager_approval')->after('reason');
            
            // Add manager approval fields
            $table->foreignId('manager_approved_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null');
            $table->timestamp('manager_approved_at')->nullable()->after('manager_approved_by');
            $table->text('manager_rejection_reason')->nullable()->after('manager_approved_at');
            
            // Add admin approval fields
            $table->foreignId('admin_approved_by')->nullable()->after('manager_rejection_reason')->constrained('users')->onDelete('set null');
            $table->timestamp('admin_approved_at')->nullable()->after('admin_approved_by');
            $table->text('admin_rejection_reason')->nullable()->after('admin_approved_at');
            
            // Add field to store selected lead IDs (JSON array)
            $table->json('selected_lead_ids')->nullable()->after('reason');
            
            // Add field to store number of leads to reassign (if not selecting specific leads)
            $table->integer('leads_count')->nullable()->after('selected_lead_ids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update pending_manager_approval back to pending
        DB::table('lead_reassignment_requests')
            ->whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])
            ->update(['status' => 'pending']);
        
        Schema::table('lead_reassignment_requests', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'manager_approved_by',
                'manager_approved_at',
                'manager_rejection_reason',
                'admin_approved_by',
                'admin_approved_at',
                'admin_rejection_reason',
                'selected_lead_ids',
                'leads_count',
                'status',
            ]);
        });
        
        Schema::table('lead_reassignment_requests', function (Blueprint $table) {
            // Revert status enum
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('reason');
        });
    }
};
