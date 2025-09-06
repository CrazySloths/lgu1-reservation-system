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
            // Add all the missing columns that Booking model expects
            $table->string('applicant_name')->nullable()->after('user_name');
            $table->string('applicant_email')->nullable()->after('applicant_name');
            $table->string('applicant_phone')->nullable()->after('applicant_email');
            $table->text('applicant_address')->nullable()->after('applicant_phone');
            $table->string('event_name')->nullable()->after('applicant_address');
            $table->text('event_description')->nullable()->after('event_name');
            $table->date('event_date')->nullable()->after('event_description');
            $table->integer('expected_attendees')->nullable()->after('event_date');
            $table->decimal('total_fee', 10, 2)->nullable()->after('expected_attendees');
            $table->text('admin_notes')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('admin_notes');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejected_reason')->nullable()->after('approved_at');
            
            // Document file paths
            $table->string('valid_id_path')->nullable()->after('rejected_reason');
            $table->string('id_back_path')->nullable()->after('valid_id_path');
            $table->string('id_selfie_path')->nullable()->after('id_back_path');
            $table->string('authorization_letter_path')->nullable()->after('id_selfie_path');
            $table->string('event_proposal_path')->nullable()->after('authorization_letter_path');
            $table->text('digital_signature')->nullable()->after('event_proposal_path');
            
            // Update start_time and end_time to be time fields instead of datetime
            $table->time('start_time')->change();
            $table->time('end_time')->change();
            
            // Add foreign key for approved_by
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'applicant_name',
                'applicant_email', 
                'applicant_phone',
                'applicant_address',
                'event_name',
                'event_description',
                'event_date',
                'expected_attendees',
                'total_fee',
                'admin_notes',
                'approved_by',
                'approved_at',
                'rejected_reason',
                'valid_id_path',
                'id_back_path',
                'id_selfie_path',
                'authorization_letter_path',
                'event_proposal_path',
                'digital_signature'
            ]);
        });
    }
};
