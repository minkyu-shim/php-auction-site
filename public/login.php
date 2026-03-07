<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../features/login_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo appUrl('css/login_style.css'); ?>">
</head>
<body>
<h2>Login</h2>

<?php if (isset($error)) echo "<p style='color:red;'>" . $error . "</p>"; ?>

<form method="post" action="">
    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="<?php echo appUrl('signup.php'); ?>">Sign up</a></p>
<p>Continue without logging in? <a href="<?php echo appUrl('index.php'); ?>">Click here</a></p>
</body>
</html>
