<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Slip - {{ $paymentSlip->slip_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: #2563eb;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }
        .org-name {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .org-subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 20px;
        }
        .slip-title {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .slip-number {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .section {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            background: #f9fafb;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .field {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .field-label {
            font-weight: bold;
            color: #374151;
            width: 40%;
        }
        .field-value {
            color: #1f2937;
            width: 55%;
            text-align: right;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            text-align: center;
            background: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .instructions {
            border: 1px solid #fbbf24;
            background: #fef3c7;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        .instructions-title {
            font-size: 16px;
            font-weight: bold;
            color: #92400e;
            margin-bottom: 10px;
        }
        .instructions ul {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 5px;
            color: #92400e;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-unpaid {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        .status-paid {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        .status-expired {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">LGU</div>
        <div class="org-name">Local Government Unit</div>
        <div class="org-subtitle">LGU1 - Reservation Payment System</div>
        <div class="slip-title">OFFICIAL PAYMENT SLIP</div>
        <div class="slip-number">{{ $paymentSlip->slip_number }}</div>
    </div>

    <!-- Amount Due -->
    <div class="amount">
        AMOUNT DUE: ₱{{ number_format($paymentSlip->amount, 2) }}
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Payment Information -->
        <div class="section">
            <div class="section-title">Payment Information</div>
            <div class="field">
                <span class="field-label">Payment Slip No:</span>
                <span class="field-value">{{ $paymentSlip->slip_number }}</span>
            </div>
            <div class="field">
                <span class="field-label">Amount Due:</span>
                <span class="field-value">₱{{ number_format($paymentSlip->amount, 2) }}</span>
            </div>
            <div class="field">
                <span class="field-label">Generated Date:</span>
                <span class="field-value">{{ $paymentSlip->created_at->format('F j, Y') }}</span>
            </div>
            <div class="field">
                <span class="field-label">Due Date:</span>
                <span class="field-value">{{ $paymentSlip->due_date->format('F j, Y') }}</span>
            </div>
            <div class="field">
                <span class="field-label">Status:</span>
                <span class="field-value">
                    <span class="status status-{{ $paymentSlip->status }}">{{ ucfirst($paymentSlip->status) }}</span>
                </span>
            </div>
            @if($paymentSlip->paid_at)
            <div class="field">
                <span class="field-label">Paid Date:</span>
                <span class="field-value">{{ $paymentSlip->paid_at->format('F j, Y g:i A') }}</span>
            </div>
            @endif
        </div>

        <!-- Citizen Information -->
        <div class="section">
            <div class="section-title">Citizen Information</div>
            <div class="field">
                <span class="field-label">Name:</span>
                <span class="field-value">{{ $paymentSlip->booking->applicant_name }}</span>
            </div>
            <div class="field">
                <span class="field-label">Email:</span>
                <span class="field-value">{{ $paymentSlip->booking->applicant_email }}</span>
            </div>
            <div class="field">
                <span class="field-label">Phone:</span>
                <span class="field-value">{{ $paymentSlip->booking->applicant_phone }}</span>
            </div>
            <div class="field">
                <span class="field-label">Address:</span>
                <span class="field-value">{{ $paymentSlip->booking->applicant_address }}</span>
            </div>
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="section" style="grid-column: 1/-1;">
        <div class="section-title">Reservation Details</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <div class="field">
                    <span class="field-label">Event Name:</span>
                    <span class="field-value">{{ $paymentSlip->booking->event_name }}</span>
                </div>
                <div class="field">
                    <span class="field-label">Facility:</span>
                    <span class="field-value">{{ $paymentSlip->booking->facility->name ?? 'N/A' }}</span>
                </div>
                <div class="field">
                    <span class="field-label">Event Date:</span>
                    <span class="field-value">{{ $paymentSlip->booking->event_date->format('F j, Y') }}</span>
                </div>
            </div>
            <div>
                <div class="field">
                    <span class="field-label">Start Time:</span>
                    <span class="field-value">{{ $paymentSlip->booking->start_time }}</span>
                </div>
                <div class="field">
                    <span class="field-label">End Time:</span>
                    <span class="field-value">{{ $paymentSlip->booking->end_time }}</span>
                </div>
                <div class="field">
                    <span class="field-label">Expected Attendees:</span>
                    <span class="field-value">{{ $paymentSlip->booking->expected_attendees }} people</span>
                </div>
            </div>
        </div>
        @if($paymentSlip->booking->event_description)
        <div style="margin-top: 15px;">
            <div class="field-label">Event Description:</div>
            <div style="margin-top: 5px; color: #1f2937;">{{ $paymentSlip->booking->event_description }}</div>
        </div>
        @endif
    </div>

    @if($paymentSlip->status === 'unpaid')
    <!-- Payment Instructions -->
    <div class="instructions">
        <div class="instructions-title">PAYMENT INSTRUCTIONS</div>
        <ul>
            <li>Present this payment slip to the LGU1 Cashier's Office</li>
            <li>Bring a valid government-issued ID for verification</li>
            <li>Pay the exact amount in cash or check made payable to "LGU1"</li>
            <li>Payment must be made before the due date to avoid expiration</li>
            <li>Keep your official receipt for your records</li>
            <li>Contact us at (123) 456-7890 for payment inquiries</li>
        </ul>
        <div style="margin-top: 15px; font-weight: bold; color: #92400e;">
            Office Hours: Monday - Friday, 8:00 AM - 5:00 PM
        </div>
    </div>
    @endif

    @if($paymentSlip->cashier_notes)
    <div class="instructions">
        <div class="instructions-title">CASHIER NOTES</div>
        <p style="margin: 0; color: #92400e;">{{ $paymentSlip->cashier_notes }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This is an official payment slip generated by the LGU1 Reservation System.</p>
        <p>Generated on {{ now()->format('F j, Y g:i A') }} | For inquiries, contact LGU1 at admin@lgu1.gov.ph</p>
    </div>
</body>
</html>
