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
        Schema::create('model_backups', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // Full class name (e.g., App\Models\Lead)
            $table->unsignedBigInteger('original_model_id')->nullable(); // Original model ID before deletion
            $table->string('model_name'); // Display name (e.g., "Lead: John Doe")
            $table->json('model_data'); // Complete model data as JSON
            $table->unsignedBigInteger('deleted_by')->nullable(); // User who requested deletion
            $table->unsignedBigInteger('approved_by')->nullable(); // User who approved deletion
            $table->text('deletion_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // 40 days from deletion
            $table->boolean('is_restored')->default(false); // Track if backup has been restored
            $table->timestamp('restored_at')->nullable();
            $table->unsignedBigInteger('restored_by')->nullable();
            $table->timestamps();
            
            $table->index('model_type');
            $table->index('original_model_id');
            $table->index('expires_at');
            $table->index('is_restored');
            $table->index(['model_type', 'original_model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_backups');
    }
};
