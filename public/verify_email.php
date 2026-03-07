<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/email_helper.php';

$success = false;
$error = "";

// Check if token is provided in URL
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Find user with this token
    $sql = "SELECT user_id, user_name, email, email_verified, token_created_at FROM users WHERE verification_token = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Check if already verified
        if ($user['email_verified'] == 1) {
            $error = "This email has already been verified. You can <a href='login.php'>login here</a>.";
        }
        // Check if token is expired (24 hours)
        else if (!isTokenValid($user['token_created_at'])) {
            $error = "This verification link has expired. Please <a href='resend_verification.php'>request a new verification email</a>.";
        }
        // Token is valid, verify the user
        else {
            $update_sql = "UPDATE users SET email_verified = 1, verification_token = NULL, token_created_at = NULL WHERE user_id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "i", $user['user_id']);

            if (mysqli_stmt_execute($update_stmt)) {
                $success = true;

                // Auto-login the user
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['email'] = $user['email'];
            } else {
                $error = "An error occurred during verification. Please try again.";
            }
        }
    } else {
        $error = "Invalid verification token. Please check your email or <a href='resend_verification.php'>request a new verification email</a>.";
    }
} else {
    $error = "No verification token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .verification-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .success-icon {
            font-size: 64px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .error-icon {
            font-size: 64px;
            color: #f44336;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin: 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #667eea;
        }
        .btn-secondary:hover {
            background-color: #5568d3;
        }
        a {
            color: #667eea;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <?php if ($success): ?>
            <div class="success-icon">✓</div>
            <h1>Email Verified Successfully!</h1>
            <p>Your email has been verified. You can now access all features of the auction site and start bidding on items.</p>
            <a href="<?php echo appUrl('index.php'); ?>" class="btn">Go to Homepage</a>
            <a href="<?php echo appUrl('index.php'); ?>" class="btn btn-secondary">Browse Auctions</a>
        <?php else: ?>
            <div class="error-icon">✗</div>
            <h1>Verification Failed</h1>
            <p><?php echo $error; ?></p>
            <a href="<?php echo appUrl('index.php'); ?>" class="btn btn-secondary">Go to Homepage</a>
        <?php endif; ?>
    </div>
</body>
</html>
