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
        Schema::table('facilities', function (Blueprint $table) {
            // Add missing columns that Facility model expects
            $table->string('location')->nullable()->after('description');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('capacity'); 
            $table->decimal('daily_rate', 10, 2)->nullable()->after('hourly_rate');
            $table->string('facility_type')->nullable()->after('daily_rate');
            $table->json('amenities')->nullable()->after('facility_type');
            $table->time('operating_hours_start')->nullable()->after('amenities');
            $table->time('operating_hours_end')->nullable()->after('operating_hours_start');
            $table->string('image_path')->nullable()->after('operating_hours_end');
            $table->decimal('latitude', 10, 8)->nullable()->after('image_path');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'hourly_rate', 
                'daily_rate',
                'facility_type',
                'amenities',
                'operating_hours_start',
                'operating_hours_end',
                'image_path',
                'latitude',
                'longitude'
            ]);
        });
    }
};
