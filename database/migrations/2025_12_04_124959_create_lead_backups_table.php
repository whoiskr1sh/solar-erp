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
        Schema::create('lead_backups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_lead_id')->nullable(); // Original lead ID before deletion
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('company')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('source');
            $table->string('status');
            $table->string('priority');
            $table->text('notes')->nullable();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->date('expected_close_date')->nullable();
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->unsignedBigInteger('channel_partner_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable(); // User who requested deletion
            $table->unsignedBigInteger('approved_by')->nullable(); // Sales manager who approved
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // 40 days from deletion
            $table->text('deletion_reason')->nullable();
            $table->timestamps();
            
            $table->index('expires_at');
            $table->index('original_lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_backups');
    }
};
