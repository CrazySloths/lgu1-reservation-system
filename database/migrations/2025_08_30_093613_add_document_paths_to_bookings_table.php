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
        Schema::table('bookings', function (Blueprint $table) {
            // Add document file path columns if they don't exist
            if (!Schema::hasColumn('bookings', 'id_back_path')) {
                $table->string('id_back_path')->nullable()->after('valid_id_path');
            }
            if (!Schema::hasColumn('bookings', 'id_selfie_path')) {
                $table->string('id_selfie_path')->nullable()->after('id_back_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['id_back_path', 'id_selfie_path']);
        });
    }
};