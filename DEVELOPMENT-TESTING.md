# Development Testing Guide - Authentication Security

## Verification Code Testing

Since this is a development environment, verification codes are handled specially for easy testing:

### Email Verification
- **In Development**: Verification links are displayed directly on the verification page
- **Email Sending**: Laravel attempts to send emails using the `log` driver (check `storage/logs/laravel.log`)
- **Manual Testing**: Click the verification link shown in the yellow development box

### SMS Verification  
- **In Development**: SMS codes are displayed directly on the verification page
- **Code Format**: 6-digit numeric code (e.g., `123456`)
- **Expiration**: 10 minutes
- **Manual Testing**: Copy the SMS code from the yellow development box and paste it into the verification form

### How to Test Registration

1. **Register a New Account**
   - Go to `/citizen/register`
   - Fill out the registration form
   - Submit the form

2. **Verification Page**
   - You'll be redirected to `/citizen/verify`
   - Look for the **yellow "Development Mode"** box at the top
   - This box contains:
     - **Email Verification Link**: Click to verify email
     - **SMS Code**: Copy and paste into the phone verification form

3. **Complete Verification**
   - Click the email verification link
   - Enter the SMS code in the phone verification form
   - Once both are verified, you'll be automatically logged in

### Development Mode Features

- ✅ Verification codes displayed on screen
- ✅ Email links clickable directly
- ✅ SMS codes copy-pasteable
- ✅ No actual email/SMS sending required
- ✅ All codes logged to `storage/logs/laravel.log`

### Production vs Development

| Feature | Development | Production |
|---------|-------------|------------|
| Email Verification | Displayed on page + logged | Sent via email service |
| SMS Verification | Displayed on page + logged | Sent via SMS service |
| Code Visibility | Visible for testing | Hidden/secure |
| Email Driver | `log` driver | Real email service |
| SMS Driver | Development display | Real SMS service |

### Troubleshooting

**If you don't see verification codes:**
1. Check that `APP_ENV=local` in your `.env` file
2. Clear config cache: `php artisan config:cache`
3. Check Laravel logs: `storage/logs/laravel.log`

**If email verification fails:**
1. The verification link is displayed on the verification page
2. You can also find it in the Laravel logs
3. Click the link directly from the yellow development box

**If SMS verification fails:**
1. The 6-digit code is displayed on the verification page
2. Copy the code from the yellow development box
3. Paste it into the verification form
4. Code expires in 10 minutes

### Security Features Included

- ✅ Rate limiting (5 failed attempts = 30 min lock)
- ✅ Token expiration (SMS: 10 min, Email: 24 hours)
- ✅ Session security
- ✅ CSRF protection
- ✅ Failed attempt tracking
- ✅ Account lockout protection

### Next Steps

After verification is complete, you can optionally set up Two-Factor Authentication (TOTP) using:
- Google Authenticator
- Authy  
- Microsoft Authenticator
- Any TOTP-compatible app

The 2FA setup includes QR code generation and recovery codes for account security.
