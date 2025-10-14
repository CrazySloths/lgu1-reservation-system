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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_id');
            $table->foreign('facility_id')->references('facility_id')->on('facilities')->onDelete('cascade');
            
            $table->enum('maintenance_type', ['repair', 'cleaning', 'inspection', 'preventive', 'emergency', 'other'])->default('repair');
            $table->string('title');
            $table->text('description');
            
            $table->string('reported_by')->nullable(); // Admin/Staff name
            $table->unsignedBigInteger('reported_by_id')->nullable(); // User ID if available
            
            $table->string('assigned_to')->nullable(); // Contractor/Staff assigned
            $table->string('assigned_contact')->nullable(); // Phone/Email of assignee
            
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            $table->date('scheduled_date')->nullable();
            $table->date('completed_date')->nullable();
            
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            
            $table->text('notes')->nullable();
            $table->text('completion_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
