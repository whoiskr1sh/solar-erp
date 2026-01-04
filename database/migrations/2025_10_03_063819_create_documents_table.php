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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size'); // in bytes
            $table->enum('category', ['proposal', 'contract', 'invoice', 'quotation', 'report', 'presentation', 'technical_spec', 'other'])->default('other');
            $table->enum('status', ['draft', 'active', 'archived', 'deleted'])->default('active');
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->json('tags')->nullable(); // For tagging documents
            $table->timestamp('expiry_date')->nullable(); // For contracts, agreements
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index(['lead_id', 'created_at']);
            $table->index(['project_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
