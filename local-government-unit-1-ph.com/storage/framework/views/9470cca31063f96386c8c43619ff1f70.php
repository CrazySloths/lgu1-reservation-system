

<?php $__env->startSection('title', 'Booking Cancelled by Citizen'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Booking Cancellation Notice</h2>
    
    <p>Dear Staff,</p>
    
    <p>A citizen has cancelled their facility booking request.</p>
    
    <div class="info-box info-box-warning">
        <h3>Cancelled Booking Details</h3>
        <p><strong>Booking Reference:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->booking_reference); ?></p>
        <p><strong>Facility:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->facility_name); ?></p>
        <p><strong>Citizen:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->applicant_name ?? $booking->user_name); ?></p>
        <p><strong>Event Date:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($booking->start_time)->format('F d, Y - h:i A')); ?></p>
        <p><strong>Status:</strong> Cancelled</p>
    </div>
    
    <?php if($reason): ?>
    <div class="info-box info-box-info">
        <h3>Cancellation Reason</h3>
        <p><?php echo new \Illuminate\Support\EncodedHtmlString($reason); ?></p>
    </div>
    <?php endif; ?>
    
    <div class="divider"></div>
    
    <p style="text-align: center;">
        <a href="<?php echo new \Illuminate\Support\EncodedHtmlString(url('/staff/bookings')); ?>" class="button">
            View All Bookings
        </a>
    </p>
    
    <p style="font-size: 14px; color: #6b7280; margin-top: 20px; text-align: center;">
        This is an automated notification from the LGU Facility Reservation System.
    </p>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/emails/booking-cancelled.blade.php ENDPATH**/ ?>