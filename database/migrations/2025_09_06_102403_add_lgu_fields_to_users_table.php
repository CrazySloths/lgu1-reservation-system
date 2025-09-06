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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('lgu_user_id')->nullable()->after('id');
            $table->string('lgu_username')->nullable()->after('lgu_user_id');
            
            // Add index for faster lookups
            $table->index('lgu_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['lgu_user_id']);
            $table->dropColumn(['lgu_user_id', 'lgu_username']);
        });
    }
};
