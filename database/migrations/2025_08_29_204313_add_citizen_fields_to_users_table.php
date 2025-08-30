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
            $table->string('role')->default('citizen')->after('email'); // citizen, admin
            $table->string('phone_number')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone_number');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('id_type')->nullable()->after('date_of_birth'); // Government ID, School ID, etc.
            $table->string('id_number')->nullable()->after('id_type');
            $table->boolean('is_verified')->default(false)->after('id_number');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone_number', 
                'address',
                'date_of_birth',
                'id_type',
                'id_number',
                'is_verified',
                'verified_at'
            ]);
        });
    }
};
