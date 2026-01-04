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
        Schema::create('duplicate_lead_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by'); // User who requested the duplicate lead
            $table->unsignedBigInteger('existing_lead_id'); // ID of the existing lead with same email
            $table->json('lead_data'); // Store the new lead data that needs approval
            $table->text('reason')->nullable(); // Reason for creating duplicate lead
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable(); // Sales Manager who approved/rejected
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('existing_lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
            $table->index('requested_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duplicate_lead_approvals');
    }
};
