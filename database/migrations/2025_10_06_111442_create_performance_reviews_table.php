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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('review_period');
            $table->date('review_date');
            $table->integer('overall_rating');
            $table->text('goals_achieved');
            $table->text('areas_for_improvement');
            $table->text('manager_comments');
            $table->text('employee_comments');
            $table->enum('status', ['draft', 'submitted', 'approved', 'completed']);
            $table->string('reviewed_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};