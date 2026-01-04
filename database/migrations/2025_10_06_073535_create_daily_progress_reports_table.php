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
        Schema::create('daily_progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('report_date');
            $table->enum('weather_condition', ['sunny', 'cloudy', 'rainy', 'stormy', 'foggy']);
            $table->text('work_performed');
            $table->decimal('work_hours', 5, 2);
            $table->integer('workers_present');
            $table->text('materials_used')->nullable();
            $table->text('equipment_used')->nullable();
            $table->text('challenges_faced')->nullable();
            $table->text('next_day_plan')->nullable();
            $table->json('photos')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['project_id', 'report_date']);
            $table->index(['status', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_progress_reports');
    }
};
