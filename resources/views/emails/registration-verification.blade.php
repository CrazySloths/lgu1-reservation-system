<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - LGU1 Portal Registration</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none !important;
            padding: 15px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        }
        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .security-note {
            background-color: #f7fafc;
            border-left: 4px solid #4299e1;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 4px 4px 0;
        }
        .security-note h3 {
            color: #2b6cb0;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .security-note p {
            color: #4a5568;
            margin: 0;
            font-size: 14px;
        }
        .footer {
            background-color: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            color: #718096;
            font-size: 14px;
            margin: 5px 0;
        }
        .alternative-link {
            background-color: #f7fafc;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .alternative-link p {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #4a5568;
        }
        .alternative-link code {
            background-color: #edf2f7;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 12px;
            word-break: break-all;
        }
        .icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            vertical-align: middle;
            margin-right: 8px;
        }
        .info-box {
            background-color: #e6fffa;
            border-left: 4px solid #38b2ac;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 4px 4px 0;
        }
        .info-box h3 {
            color: #234e52;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .info-box p {
            color: #285e61;
            margin: 0;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <!-- Replace this with your actual logo -->
                <div style="margin-right: 15px;">
                    <img src="{{ asset('images/lgu-logo.png') }}" 
                        alt="LGU1 Logo" 
                        width="48" 
                        height="48" 
                        style="border-radius: 8px;">
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 28px; font-weight: 600;">LGU1 Portal</h1>
                </div>
            </div>
            <p>Local Government Unit Facility Reservation System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $name }}!
            </div>

            <div class="message">
                <p>Thank you for starting your registration with the LGU1 Portal!</p>
                
                <p>To complete your account setup, we need to verify your email address. Please click the button below to verify your email:</p>
            </div>

            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button" target="_blank" style="color: white !important; text-decoration: none !important;">
                    ✉️ Verify Email Address
                </a>
            </div>

            <div class="info-box">
                <h3>🚀 What happens next?</h3>
                <p><strong>Your registration data is temporarily stored</strong> and will be saved to your account only after you complete both email and SMS verification. This ensures your data is secure and your identity is confirmed.</p>
            </div>

            <div class="security-note">
                <h3>🔒 Security Information</h3>
                <p><strong>This verification link will expire in 30 minutes</strong> for your security. After email verification, you'll receive an SMS code to complete the registration process.</p>
            </div>

            <div class="alternative-link">
                <p><strong>Having trouble with the button?</strong> Copy and paste the following link into your browser:</p>
                <code>{{ $verificationUrl }}</code>
            </div>

            <div class="message">
                <p><strong>Registration Process:</strong></p>
                <ul style="color: #4a5568; padding-left: 20px;">
                    <li><strong>Step 1:</strong> Verify your email address (current step) ✉️</li>
                    <li><strong>Step 2:</strong> Verify your phone number with SMS code 📱</li>
                    <li><strong>Step 3:</strong> Account created and ready to use! ✅</li>
                    <li><strong>Bonus:</strong> Optionally set up two-factor authentication</li>
                </ul>
            </div>

            <div class="security-note">
                <h3>🛡️ Why Two-Step Verification?</h3>
                <p>We implement email and SMS verification to ensure account security and prevent unauthorized registrations. Your personal information and facility reservations remain protected.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>LGU1 Portal - Public Facility Reservation System</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you didn't request this registration, please ignore this email.</p>
            <p style="margin-top: 20px; font-size: 12px;">
                Email sent to: {{ $email }}<br>
                Registration initiated: {{ now()->format('F j, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
</body>
</html>
