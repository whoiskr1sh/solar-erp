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
        Schema::create('company_policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content');
            $table->enum('category', [
                'hr_policies', 
                'safety_policies', 
                'it_policies', 
                'financial_policies', 
                'operational_policies', 
                'quality_policies',
                'environmental_policies',
                'other'
            ])->default('other');
            $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->date('effective_date');
            $table->date('review_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('version')->default('1.0');
            $table->json('attachments')->nullable();
            $table->json('approval_workflow')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('requires_acknowledgment')->default(false);
            $table->text('acknowledgment_instructions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_policies');
    }
};
