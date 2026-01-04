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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('aadhar_path')->nullable()->after('cancelled_cheque_path');
            $table->string('pan_path')->nullable()->after('aadhar_path');
            $table->string('other_document_name')->nullable()->after('pan_path');
            $table->string('other_document_path')->nullable()->after('other_document_name');
            $table->string('passport_photo_path')->nullable()->after('other_document_path');
            $table->string('site_photo_pre_installation_path')->nullable()->after('passport_photo_path');
            $table->string('site_photo_post_installation_path')->nullable()->after('site_photo_pre_installation_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'aadhar_path',
                'pan_path',
                'other_document_name',
                'other_document_path',
                'passport_photo_path',
                'site_photo_pre_installation_path',
                'site_photo_post_installation_path',
            ]);
        });
    }
};
