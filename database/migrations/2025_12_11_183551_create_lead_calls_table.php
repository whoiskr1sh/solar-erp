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
        // Drop table if it exists with wrong structure
        Schema::dropIfExists('lead_calls');
        
        Schema::create('lead_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('caller_id'); // Person A - who made the call
            $table->unsignedBigInteger('assigned_user_id')->nullable(); // Person B - who the lead is assigned to (for incentive tracking)
            $table->string('status')->nullable(); // Call status/outcome
            $table->text('notes')->nullable(); // Call notes
            $table->integer('duration_seconds')->nullable(); // Call duration
            $table->timestamp('called_at')->useCurrent(); // When the call was made
            $table->timestamps();
            
            // Foreign keys - only add if tables exist
            if (Schema::hasTable('leads')) {
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            }
            if (Schema::hasTable('users')) {
                $table->foreign('caller_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            }
            
            // Indexes for performance
            $table->index(['lead_id', 'called_at']);
            $table->index(['caller_id', 'called_at']);
            $table->index(['assigned_user_id', 'called_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_calls');
    }
};
