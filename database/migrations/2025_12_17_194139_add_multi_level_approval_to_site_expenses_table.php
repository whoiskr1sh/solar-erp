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
        Schema::table('site_expenses', function (Blueprint $table) {
            // Add approval level tracking (hr -> admin -> approved)
            if (!Schema::hasColumn('site_expenses', 'approval_level')) {
                $table->enum('approval_level', ['hr', 'admin', 'approved', 'rejected'])->default('hr')->after('status');
            }
            
            // HR approval fields
            if (!Schema::hasColumn('site_expenses', 'hr_approved_by')) {
                $table->foreignId('hr_approved_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('site_expenses', 'hr_approved_at')) {
                $table->timestamp('hr_approved_at')->nullable()->after('hr_approved_by');
            }
            if (!Schema::hasColumn('site_expenses', 'hr_rejection_reason')) {
                $table->text('hr_rejection_reason')->nullable()->after('rejection_reason');
            }
            
            // Admin approval fields
            if (!Schema::hasColumn('site_expenses', 'admin_rejection_reason')) {
                $table->text('admin_rejection_reason')->nullable()->after('hr_rejection_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_expenses', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('site_expenses', 'hr_approved_by')) {
                $table->dropForeign(['hr_approved_by']);
            }
            
            // Then drop columns
            $columns = [
                'approval_level',
                'hr_approved_by',
                'hr_approved_at',
                'hr_rejection_reason',
                'admin_rejection_reason'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('site_expenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
