<?php
session_start();
include '../includes/db.php';
include '../includes/email_helper.php';

$resend_success = false;
$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];

    if(empty($email)){
        $error = "Email is required";
    } else {
        // Check if user exists
        $sql = "SELECT user_id, user_name, email, email_verified FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($user = mysqli_fetch_assoc($result)){
            // Check if already verified
            if($user['email_verified'] == 1){
                $error = "This email is already verified. You can <a href='login.php'>login here</a>.";
            } else {
                // Generate new verification token
                $verification_token = generateVerificationToken();
                $token_created_at = date('Y-m-d H:i:s');

                // Update user with new token
                $update_sql = "UPDATE users SET verification_token = ?, token_created_at = ? WHERE user_id = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "ssi", $verification_token, $token_created_at, $user['user_id']);

                if(mysqli_stmt_execute($update_stmt)){
                    // Send verification email
                    if(sendVerificationEmail($email, $user['user_name'], $verification_token)){
                        $resend_success = true;
                    } else {
                        $error = "Failed to send verification email. Please try again later.";
                    }
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        } else {
            // Don't reveal if email exists or not (security best practice)
            $resend_success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Verification Email</title>
    <link rel="stylesheet" href="/public/css/login_style.css">
    <style>
        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d7ff;
            color: #004085;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<h2>Resend Verification Email</h2>

<?php if ($resend_success): ?>
    <div class="success-message">
        <h3>Verification Email Sent!</h3>
        <p>If an account exists with that email, a verification link has been sent.</p>
        <p>Please check your inbox and click the verification link to activate your account.</p>
        <p><strong>Note:</strong> The verification link will expire in 24 hours.</p>
    </div>
    <p><a href="login.php">Back to Login</a> | <a href="index.php">Go to Homepage</a></p>
<?php else: ?>
    <div class="info-box">
        <p>Enter your email address to receive a new verification link.</p>
    </div>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post" action="">
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <button type="submit">Resend Verification Email</button>
    </form>

    <p><a href="login.php">Back to Login</a> | <a href="signup.php">Create New Account</a></p>
<?php endif; ?>
</body>
</html>
