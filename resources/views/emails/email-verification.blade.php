<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - LGU1 Portal</title>
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
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
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
            <h1>🏛️ LGU1 Portal</h1>
            <p>Local Government Unit Facility Reservation System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $user->first_name }} {{ $user->last_name }}!
            </div>

            <div class="message">
                <p>Welcome to the LGU1 Portal! Thank you for registering your account with us.</p>
                
                <p>To complete your registration and secure your account, please verify your email address by clicking the button below:</p>
            </div>

            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    ✉️ Verify Email Address
                </a>
            </div>

            <div class="security-note">
                <h3>🔒 Security Information</h3>
                <p><strong>This verification link will expire in 24 hours</strong> for your security. If you don't verify within this time, you'll need to request a new verification email.</p>
            </div>

            <div class="alternative-link">
                <p><strong>Having trouble with the button?</strong> Copy and paste the following link into your browser:</p>
                <code>{{ $verificationUrl }}</code>
            </div>

            <div class="message">
                <p><strong>What happens after verification?</strong></p>
                <ul style="color: #4a5568; padding-left: 20px;">
                    <li>Your email will be verified ✅</li>
                    <li>You'll need to verify your phone number with an SMS code</li>
                    <li>Optionally set up two-factor authentication for enhanced security</li>
                    <li>Full access to reserve public facilities</li>
                </ul>
            </div>

            <div class="security-note">
                <h3>🛡️ Account Security</h3>
                <p>For your protection, we implement multiple verification steps including email verification, SMS verification, and optional two-factor authentication. This ensures your account remains secure and you receive important notifications about your reservations.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>LGU1 Portal - Public Facility Reservation System</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you didn't create an account with us, please ignore this email.</p>
            <p style="margin-top: 20px; font-size: 12px;">
                Email sent to: {{ $user->email }}<br>
                Account registered: {{ $user->created_at->format('F j, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
</body>
</html>
