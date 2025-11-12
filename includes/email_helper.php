<?php
/**
 * Email Helper Functions
 * Handles sending verification emails and other email notifications
 */

/**
 * Send verification email to new user
 * @param string $email User's email address
 * @param string $user_name User's name
 * @param string $verification_token Unique verification token
 * @return bool True if email sent successfully
 */
function sendVerificationEmail($email, $user_name, $verification_token) {
    // Get the site URL (adjust based on your local setup)
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $verification_link = $protocol . "://" . $host . "/public/verify_email.php?token=" . $verification_token;

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

    // Send email using PHP's mail() function
    // Note: This requires a mail server to be configured on your system
    // For local development with XAMPP, you may need to configure sendmail or use a library like PHPMailer
    return mail($email, $subject, $message, $headers);
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
