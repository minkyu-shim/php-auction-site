<?php
session_start();
include '../includes/db.php'; // connecting to db.php for db access (require)
include "../features/login_auth.php"; // checks user login info
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/public/css/login_style.css">
</head>
<body>
<h2>Login</h2>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post" action="">
    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="signup.php">Sign up</a></p>
<p>Continue without logging in? <a href="index.php">Click here</a></p>
</body>
</html>