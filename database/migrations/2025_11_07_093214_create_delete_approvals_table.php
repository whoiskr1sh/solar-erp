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
        Schema::create('delete_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // e.g., 'App\Models\LeaveRequest'
            $table->unsignedBigInteger('model_id'); // ID of the model to delete
            $table->unsignedBigInteger('requested_by'); // User who requested deletion
            $table->string('model_name'); // Human readable name (e.g., 'Leave Request #123')
            $table->text('reason')->nullable(); // Reason for deletion
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable(); // Admin who approved/rejected
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('model_data')->nullable(); // Store model data before deletion
            $table->timestamps();
            
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['model_type', 'model_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delete_approvals');
    }
};
