<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Required</title>
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
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .warning-icon {
            font-size: 64px;
            color: #ff9800;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="warning-icon">⚠</div>
        <h1>Email Verification Required</h1>
        <p>Your email address has not been verified yet. Please check your inbox for a verification email and click the link to verify your account.</p>
        <p>You need to verify your email before you can place bids or upload items.</p>
        <a href="<?php echo appUrl('resend_verification.php'); ?>" class="btn">Resend Verification Email</a>
        <a href="<?php echo appUrl('index.php'); ?>" class="btn btn-secondary">Back to Homepage</a>
    </div>
</body>
</html>
