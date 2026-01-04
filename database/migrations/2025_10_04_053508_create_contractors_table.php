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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->string('contractor_code')->unique();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('country')->default('India');
            $table->enum('contractor_type', ['individual', 'company', 'partnership', 'subcontractor']);
            $table->string('pan_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('specialization')->nullable();
            $table->json('skills')->nullable();
            $table->text('experience_description')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->string('currency', 3)->default('INR');
            $table->enum('availability', ['available', 'busy', 'unavailable', 'on_project'])->default('available');
            $table->text('availability_notes')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'blacklisted'])->default('active');
            $table->text('status_notes')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->integer('total_projects')->default(0);
            $table->decimal('total_value', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->json('certifications')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['status', 'availability']);
            $table->index(['contractor_type', 'status']);
            $table->index(['city', 'state']);
            $table->index(['specialization']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
    }
};