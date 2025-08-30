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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable(); // For backward compatibility
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone', 20);
            $table->text('applicant_address');
            $table->string('event_name');
            $table->text('event_description')->nullable();
            $table->date('event_date');
            $table->string('start_time');
            $table->string('end_time');
            $table->integer('expected_attendees');
            $table->decimal('total_fee', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            
            // File upload paths
            $table->string('valid_id_path')->nullable();
            $table->string('authorization_letter_path')->nullable();
            $table->string('event_proposal_path')->nullable();
            $table->text('digital_signature')->nullable();
            
            // Additional fields for compatibility
            $table->date('booking_date')->nullable(); // For backward compatibility
            $table->text('notes')->nullable(); // For backward compatibility
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('facility_id')->references('facility_id')->on('facilities')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for better performance
            $table->index(['facility_id', 'event_date']);
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};