# Email Configuration Setup Guide

## Setting Up Real Email Sending for Gmail

To test email verification with real Gmail accounts, you need to configure Laravel to use Gmail's SMTP server.

### Step 1: Create or Update Your .env File

If you don't have a `.env` file in your project root, create one. Add these mail configuration settings:

```env
# Basic App Settings
APP_NAME="LGU1 Portal"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration (adjust as needed)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lgu1_reservation_system
DB_USERNAME=root
DB_PASSWORD=

# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_gmail_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@lgu1portal.local"
MAIL_FROM_NAME="${APP_NAME}"

# Session and Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
```

### Step 2: Gmail App Password Setup

**Important**: You cannot use your regular Gmail password for SMTP. You need to create an "App Password".

#### For Gmail Accounts:

1. **Enable 2-Factor Authentication** on your Gmail account (required for app passwords)
2. Go to [Google Account Settings](https://myaccount.google.com/)
3. Go to **Security** → **2-Step Verification** → **App passwords**
4. Generate a new app password for "Mail"
5. Copy the 16-character password (it looks like: `abcd efgh ijkl mnop`)

#### Update Your .env File:
```env
MAIL_USERNAME=your_actual_gmail@gmail.com
MAIL_PASSWORD=abcdefghijklmnop  # Your 16-character app password (no spaces)
```

### Step 3: Alternative Email Services

If you prefer not to use Gmail, here are other options:

#### Mailtrap (For Testing)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

#### Outlook/Hotmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your_outlook@outlook.com
MAIL_PASSWORD=your_outlook_password
MAIL_ENCRYPTION=tls
```

### Step 4: Clear Laravel Configuration Cache

After updating your `.env` file, run:

```bash
php artisan config:cache
```

### Step 5: Test Email Sending

1. **Register a new account** with a real email address
2. **Check the verification page** - you should see:
   - ✅ A yellow development box (backup display)
   - ✅ An actual email sent to your Gmail inbox

3. **Check your email inbox** for a professional verification email with:
   - ✅ LGU1 Portal branding
   - ✅ Clear verification instructions
   - ✅ Clickable verification button
   - ✅ Security information

### Troubleshooting

#### Email Not Received?
1. **Check spam folder** - SMTP emails sometimes go to spam
2. **Check Laravel logs**: `storage/logs/laravel.log`
3. **Verify SMTP credentials** in your `.env` file
4. **Test with `php artisan tinker`**:
   ```php
   Mail::raw('Test email', function($message) {
       $message->to('your-test@gmail.com')->subject('Test');
   });
   ```

#### Common Errors:

**"Authentication failed"**
- Your Gmail app password is incorrect
- 2FA is not enabled on Gmail
- Username/password has typos

**"Connection refused"**
- MAIL_HOST or MAIL_PORT is incorrect
- Firewall blocking SMTP connections

**"TLS connection failed"**
- Try changing `MAIL_ENCRYPTION=ssl` instead of `tls`
- Or try `MAIL_PORT=465` with `ssl`

### Development vs Production

The system now supports both:

✅ **Development Mode**: 
- Email also displayed on verification page
- Backup session storage for testing
- Detailed error logging

✅ **Production Mode**:
- Professional HTML email template
- Real SMTP sending
- Proper error handling
- Security-focused design

### Email Template Features

The verification email includes:
- 🏛️ LGU1 Portal branding
- 📧 Professional HTML design
- 🔒 Security information
- ⏰ Expiration notice (24 hours)
- 📱 Mobile-responsive design
- 🔗 Alternative text link (if button doesn't work)
- 🛡️ Account security explanation

### Security Features

- ✅ **24-hour expiration** for email verification links
- ✅ **Unique tokens** for each verification
- ✅ **Rate limiting** to prevent abuse
- ✅ **Secure session handling**
- ✅ **CSRF protection** on all forms
- ✅ **Proper error logging** without exposing sensitive data
