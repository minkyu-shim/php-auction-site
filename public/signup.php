<?php
session_start();
include '../includes/db.php';
include '../includes/email_helper.php';

$signup_success = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($user_name) || empty($email) || empty($password)){
        $error = "All fields are required";
    } else {
        // check if the input email is valid (not already used)
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_fetch_assoc($result)){
            $error = "Email already exists";
        } else {
            // Hash the password, we haven't learned this from school yet.
            // But it says that password hashing is essential when storing them in db
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Generate verification token
            $verification_token = generateVerificationToken();
            $token_created_at = date('Y-m-d H:i:s');

            // Creating a new user in my db with verification token
            $sql = "INSERT INTO users (user_name, email, password, email_verified, verification_token, token_created_at) VALUES (?, ?, ?, 0, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $user_name, $email, $hashed_password, $verification_token, $token_created_at);

            if(mysqli_stmt_execute($stmt)){
                // Send verification email
                if(sendVerificationEmail($email, $user_name, $verification_token)){
                    $signup_success = true;
                } else {
                    // Email sending failed, but account was created
                    // You might want to log this error
                    $error = "Account created but verification email could not be sent. Please contact support.";
                }
            } else {
                $error = "Something went wrong";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/public/css/signup_style.css">
    <style>
        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .success-message h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
<h2>Signup</h2>

<?php if ($signup_success): ?>
    <div class="success-message">
        <h3>Registration Successful!</h3>
        <p>A verification email has been sent to your email address.</p>
        <p>Please check your inbox and click the verification link to activate your account.</p>
        <p><strong>Note:</strong> The verification link will expire in 24 hours.</p>
        <p>Didn't receive the email? <a href="resend_verification.php">Resend verification email</a></p>
    </div>
    <p><a href="login.php">Back to Login</a></p>
<?php else: ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post" action="">
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <label for="username">Username</label><br>
        <input type="text" name="user_name" id="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">SignUp</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
<?php endif; ?>
</body>
</html>
