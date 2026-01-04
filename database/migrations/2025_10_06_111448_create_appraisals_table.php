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
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('appraisal_type');
            $table->date('appraisal_date');
            $table->date('next_review_date');
            $table->integer('performance_score');
            $table->text('strengths');
            $table->text('weaknesses');
            $table->text('development_plan');
            $table->text('manager_feedback');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled']);
            $table->string('appraiser_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};