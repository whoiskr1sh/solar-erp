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
        Schema::table('advances', function (Blueprint $table) {
            // Add approval level tracking
            $table->enum('approval_level', ['manager', 'hr', 'admin', 'approved', 'rejected'])->default('manager')->after('status');
            
            // Manager approval fields
            $table->foreignId('manager_approved_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null');
            $table->timestamp('manager_approved_at')->nullable()->after('approved_at');
            $table->text('manager_rejection_reason')->nullable()->after('rejection_reason');
            
            // HR approval fields
            $table->foreignId('hr_approved_by')->nullable()->after('manager_approved_at')->constrained('users')->onDelete('set null');
            $table->timestamp('hr_approved_at')->nullable()->after('hr_approved_by');
            $table->text('hr_rejection_reason')->nullable()->after('manager_rejection_reason');
            
            // Admin approval fields (keeping existing approved_by for final approval)
            $table->text('admin_rejection_reason')->nullable()->after('hr_rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advances', function (Blueprint $table) {
            $table->dropColumn([
                'approval_level',
                'manager_approved_by',
                'manager_approved_at',
                'manager_rejection_reason',
                'hr_approved_by',
                'hr_approved_at',
                'hr_rejection_reason',
                'admin_rejection_reason'
            ]);
        });
    }
};
