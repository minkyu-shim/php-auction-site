<?php
/**
 * Email Helper Functions
 * Handles sending verification emails and other email notifications
 */

require_once __DIR__ . '/config.php';

/**
 * Send verification email to new user
 * @param string $email User's email address
 * @param string $user_name User's name
 * @param string $verification_token Unique verification token
 * @return bool True if email sent successfully
 */
function sendVerificationEmail($email, $user_name, $verification_token) {
    $verification_link = appAbsoluteUrl('verify_email.php?token=' . urlencode($verification_token));

    // Email subject
    $subject = "Verify your email - Minkyu's Auction Site";

    // Email body (HTML)
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
            .content { background-color: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
            .button {
                display: inline-block;
                padding: 12px 30px;
                background-color: #4CAF50;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin: 20px 0;
            }
            .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Welcome to Minkyu's Auction Site!</h1>
            </div>
            <div class='content'>
                <h2>Hello $user_name,</h2>
                <p>Thank you for signing up! Please verify your email address to complete your registration and start bidding on items.</p>
                <p>Click the button below to verify your email:</p>
                <p style='text-align: center;'>
                    <a href='$verification_link' class='button'>Verify Email Address</a>
                </p>
                <p>Or copy and paste this link into your browser:</p>
                <p style='word-break: break-all; background-color: #fff; padding: 10px; border: 1px solid #ddd;'>
                    $verification_link
                </p>
                <p><strong>This link will expire in 24 hours.</strong></p>
                <p>If you didn't create an account, please ignore this email.</p>
            </div>
            <div class='footer'>
                <p>&copy; 2025 Minkyu's Auction Site. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@auction-site.com" . "\r\n";

    $delivery_mode = getenv('MAIL_DELIVERY') ?: 'mail';
    $fallback_mode = getenv('MAIL_FALLBACK') ?: 'log';

    if ($delivery_mode === 'log') {
        return logEmailPreview($email, $subject, $message, $verification_link);
    }

    $sent = mail($email, $subject, $message, $headers);
    if ($sent) {
        return true;
    }

    return $fallback_mode === 'log'
        ? logEmailPreview($email, $subject, $message, $verification_link)
        : false;
}

/**
 * Generate a secure random token for email verification
 * @return string 64-character hexadecimal token
 */
function generateVerificationToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Check if verification token is still valid (within 24 hours)
 * @param string $token_created_at Timestamp when token was created
 * @return bool True if token is still valid
 */
function isTokenValid($token_created_at) {
    $created_time = strtotime($token_created_at);
    $current_time = time();
    $time_difference = $current_time - $created_time;

    // Token valid for 24 hours (86400 seconds)
    return $time_difference < 86400;
}

function logEmailPreview($email, $subject, $message, $verification_link) {
    $mail_dir = appStoragePath('mail');
    if (!is_dir($mail_dir) && !mkdir($mail_dir, 0777, true) && !is_dir($mail_dir)) {
        return false;
    }

    $safe_recipient = preg_replace('/[^a-z0-9]+/i', '_', strtolower($email));
    $timestamp = date('Ymd_His');
    $file_prefix = $timestamp . '_' . trim($safe_recipient, '_');

    $html_path = $mail_dir . '/' . $file_prefix . '.html';
    $text_path = $mail_dir . '/latest_verification_link.txt';

    $email_preview = "<!-- Subject: {$subject} -->\n" . $message;

    $html_written = file_put_contents($html_path, $email_preview);
    $text_written = file_put_contents($text_path, $verification_link . PHP_EOL);

    return $html_written !== false && $text_written !== false;
}
