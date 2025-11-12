# Email Verification System - Setup Guide

This guide will help you set up the email verification system for your auction website.

## 📋 Overview

The email verification system adds an extra layer of security and trust to your auction platform by requiring users to verify their email addresses before they can:
- Place bids on items
- Upload items for auction
- Access their item management page

## 🎯 Features Implemented

1. **Email Verification on Signup** - New users receive a verification email with a secure token
2. **Verification Token Expiry** - Tokens expire after 24 hours for security
3. **Login Prevention** - Unverified users cannot log in
4. **Resend Verification Email** - Users can request a new verification link
5. **Auto-Login After Verification** - Users are automatically logged in after verifying
6. **Protected Pages** - All sensitive actions require verified email

## 🗄️ Database Setup

### Step 1: Run the Migration Script

You need to add new columns to your `users` table. Run this SQL script:

**File:** `sql/add_email_verification.sql`

```sql
ALTER TABLE `users`
ADD COLUMN `email_verified` TINYINT(1) DEFAULT 0 AFTER `password`,
ADD COLUMN `verification_token` VARCHAR(64) DEFAULT NULL AFTER `email_verified`,
ADD COLUMN `token_created_at` DATETIME DEFAULT NULL AFTER `verification_token`;

UPDATE `users` SET `email_verified` = 1 WHERE `email_verified` = 0;
```

**How to run:**
1. Open phpMyAdmin
2. Select your `auction_db` database
3. Go to the SQL tab
4. Paste the contents of `sql/add_email_verification.sql`
5. Click "Go"

The script will:
- Add `email_verified` column (0 = not verified, 1 = verified)
- Add `verification_token` column (stores unique token)
- Add `token_created_at` column (tracks token expiry)
- Mark all existing users as verified (so they can continue using the site)

## 📧 Email Configuration

### For Local Development (XAMPP)

By default, PHP's `mail()` function won't work on XAMPP without configuration. You have two options:

#### Option 1: Configure XAMPP Sendmail (Recommended for Testing)

1. **Edit php.ini** (in `C:\xampp\php\php.ini`):
```ini
[mail function]
SMTP=smtp.gmail.com
smtp_port=587
sendmail_from=your-email@gmail.com
sendmail_path="\"C:\xampp\sendmail\sendmail.exe\" -t"
```

2. **Edit sendmail.ini** (in `C:\xampp\sendmail\sendmail.ini`):
```ini
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=your-email@gmail.com
auth_password=your-app-password
force_sender=your-email@gmail.com
```

3. **Get Gmail App Password:**
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate password for "Mail"
   - Use this password in sendmail.ini

4. **Restart Apache** in XAMPP Control Panel

#### Option 2: Use PHPMailer Library (Recommended for Production)

For a more reliable solution, consider using PHPMailer:

```bash
composer require phpmailer/phpmailer
```

Then modify `includes/email_helper.php` to use PHPMailer instead of `mail()`.

### For Production

For production deployment, use:
- **SendGrid** (free tier: 100 emails/day)
- **Mailgun** (free tier: 5,000 emails/month)
- **AWS SES** (62,000 emails/month on free tier)

## 📁 Files Created/Modified

### New Files Created:

1. **`sql/add_email_verification.sql`** - Database migration script
2. **`includes/email_helper.php`** - Email sending functions
3. **`includes/verification_check.php`** - Helper to check email verification status
4. **`public/verify_email.php`** - Handles email verification when users click link
5. **`public/verification_required.php`** - Warning page for unverified users
6. **`public/resend_verification.php`** - Allows users to request new verification email

### Modified Files:

1. **`public/signup.php`** - Sends verification email on registration
2. **`features/login_auth.php`** - Prevents login for unverified users
3. **`public/login.php`** - Updated to display HTML error messages
4. **`public/bid.php`** - Requires email verification
5. **`public/upload_item.php`** - Requires email verification
6. **`public/my_items.php`** - Requires email verification

## 🚀 How It Works

### User Signup Flow:

1. User fills out signup form
2. System creates account with `email_verified = 0`
3. System generates unique 64-character verification token
4. Verification email sent with link: `verify_email.php?token=XXXXXX`
5. User sees success message (not auto-logged in)

### Email Verification Flow:

1. User clicks link in email
2. `verify_email.php` validates token
3. Checks if token is expired (>24 hours)
4. If valid: Sets `email_verified = 1`, clears token, auto-logs in user
5. User redirected to homepage

### Login Flow:

1. User enters email and password
2. Password verified
3. **NEW:** System checks if `email_verified = 1`
4. If not verified: Login blocked, shown error with resend link
5. If verified: Login successful

### Protected Pages:

All pages with sensitive actions now call `checkEmailVerification()`:
- `bid.php` - Placing bids
- `upload_item.php` - Uploading items
- `my_items.php` - Managing items

If unverified user somehow has active session:
- Session destroyed
- Redirected to `verification_required.php`

## 🧪 Testing

### Test the Complete Flow:

1. **Create New Account:**
   - Go to `signup.php`
   - Register with a real email address you can access
   - Check inbox for verification email

2. **Try to Login (Should Fail):**
   - Go to `login.php`
   - Try to log in
   - Should see error: "Please verify your email..."

3. **Verify Email:**
   - Click link in verification email
   - Should see success page
   - Should be automatically logged in

4. **Test Protected Pages:**
   - Try to place a bid (should work)
   - Try to upload item (should work)

5. **Test Resend Feature:**
   - Create another account
   - Go to `resend_verification.php`
   - Enter email
   - Should receive new verification email

### Test Token Expiry:

To test 24-hour expiry:
1. Create test user
2. Manually update database:
   ```sql
   UPDATE users SET token_created_at = DATE_SUB(NOW(), INTERVAL 25 HOUR) WHERE email = 'test@test.com';
   ```
3. Try to use verification link
4. Should see "verification link has expired" error

## 🔒 Security Features

1. **Secure Token Generation** - Uses `random_bytes(32)` for cryptographically secure tokens
2. **Token Expiry** - Tokens expire after 24 hours
3. **One-Time Use** - Tokens are cleared after successful verification
4. **SQL Injection Protection** - All queries use prepared statements
5. **Password Hashing** - Passwords stored with bcrypt
6. **Session Security** - Unverified users can't maintain sessions

## 🐛 Troubleshooting

### Emails Not Sending:

**Check:**
1. Is Apache restarted after configuring sendmail?
2. Is Gmail "Less secure app access" enabled? (if using Gmail)
3. Are you using App Password instead of regular password?
4. Check Apache error logs: `C:\xampp\apache\logs\error.log`
5. Check sendmail logs: `C:\xampp\sendmail\sendmail.log`

**Debug email sending:**
Add this to test email functionality:
```php
$result = mail("your-email@example.com", "Test", "Test message");
var_dump($result); // Should print: bool(true)
```

### Verification Link Not Working:

**Check:**
1. Is token in URL correct?
2. Has token expired (>24 hours)?
3. Was token already used?
4. Check database: `SELECT * FROM users WHERE verification_token = 'TOKEN'`

### Still Can't Login After Verification:

**Check:**
```sql
SELECT email_verified FROM users WHERE email = 'user@example.com';
```
Should return `1`. If not, manually update:
```sql
UPDATE users SET email_verified = 1 WHERE email = 'user@example.com';
```

## 📝 Customization

### Change Token Expiry Time:

Edit `includes/email_helper.php`, line 86:
```php
// Change from 24 hours (86400 seconds) to desired time
return $time_difference < 86400; // 24 hours
return $time_difference < 7200;  // 2 hours
return $time_difference < 172800; // 48 hours
```

### Change Email Template:

Edit `includes/email_helper.php` to customize:
- Subject line
- Email colors and styling
- Button text
- Email content

### Add Email Verification Badge:

Show a verified badge next to verified users:
```php
<?php if($user['email_verified'] == 1): ?>
    <span style="color: green;">✓ Verified</span>
<?php endif; ?>
```

## 🎯 Next Steps

Consider adding:
1. **Email notifications** for outbid, auction end, etc.
2. **Phone verification** for additional security
3. **Two-factor authentication** (2FA)
4. **User rating system** to build trust
5. **Transaction history** for transparency

## 📚 Resources

- [PHP mail() function](https://www.php.net/manual/en/function.mail.php)
- [PHPMailer Documentation](https://github.com/PHPMailer/PHPMailer)
- [Configuring XAMPP Sendmail](https://blog.mailtrap.io/xampp-sendmail/)
- [Gmail App Passwords](https://support.google.com/accounts/answer/185833)

## ✅ Verification Checklist

- [ ] Database migration completed
- [ ] Email configuration set up (sendmail or PHPMailer)
- [ ] Apache restarted
- [ ] Test signup creates unverified user
- [ ] Verification email received
- [ ] Clicking link verifies account
- [ ] Login works after verification
- [ ] Login blocked before verification
- [ ] Protected pages require verification
- [ ] Resend verification works
- [ ] Token expiry works correctly

---

**Author:** Claude Code
**Date:** November 2025
**Version:** 1.0
