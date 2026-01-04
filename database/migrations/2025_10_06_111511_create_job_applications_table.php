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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->string('job_title');
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone');
            $table->text('resume_path');
            $table->text('cover_letter');
            $table->enum('status', ['applied', 'screening', 'interview', 'selected', 'rejected']);
            $table->date('application_date');
            $table->date('interview_date')->nullable();
            $table->text('interview_notes')->nullable();
            $table->string('interviewer_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};