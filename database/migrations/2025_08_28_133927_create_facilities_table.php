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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id('facility_id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->integer('capacity')->nullable();
            $table->decimal('rate_per_hour', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Add this line
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};