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
        Schema::create('payment_slips', function (Blueprint $table) {
            $table->id();
            $table->string('slip_number')->unique(); // PS-2025-0001
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // citizen
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade'); // admin who approved
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['unpaid', 'paid', 'expired'])->default('unpaid');
            $table->datetime('due_date'); // payment deadline
            $table->datetime('paid_at')->nullable();
            $table->string('payment_method')->nullable(); // cash, check, online
            $table->text('cashier_notes')->nullable();
            $table->foreignId('paid_by_cashier')->nullable()->constrained('users')->onDelete('set null'); // admin/cashier who processed payment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_slips');
    }
};
