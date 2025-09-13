<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Map central SSO user_id to local users.external_id
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // external_id is nullable for existing/local users; unique when present
            $table->string('external_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Rollback
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop unique index then column (index name follows Laravel's convention)
            if (Schema::hasColumn('users', 'external_id')) {
                $table->dropUnique('users_external_id_unique');
                $table->dropColumn('external_id');
            }
        });
    }
};
