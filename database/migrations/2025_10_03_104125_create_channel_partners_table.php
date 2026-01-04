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
        Schema::create('channel_partners', function (Blueprint $table) {
            $table->id();
            $table->string('partner_code')->unique();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->default('India');
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('website')->nullable();
            $table->enum('partner_type', ['distributor', 'dealer', 'installer', 'consultant', 'other'])->default('distributor');
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('pending');
            $table->decimal('commission_rate', 5, 2)->default(0); // Percentage
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('outstanding_amount', 15, 2)->default(0);
            $table->date('agreement_start_date')->nullable();
            $table->date('agreement_end_date')->nullable();
            $table->text('specializations')->nullable(); // JSON array of specializations
            $table->text('notes')->nullable();
            $table->json('bank_details')->nullable(); // Bank account details
            $table->json('documents')->nullable(); // Document attachments
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_partners');
    }
};