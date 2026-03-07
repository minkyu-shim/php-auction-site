<?php

require_once __DIR__ . '/../includes/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        $error = "Email or Password is empty";
    }else{
        // validate user from db table users
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($user = mysqli_fetch_assoc($result)){
            // Check Password
            // Check variable name, $password is also used in db.php for db connection, if not working change
            if(password_verify($password, $user['password'])){
                // Check if email is verified
                if($user['email_verified'] == 0){
                    $error = "Please verify your email before logging in. Check your inbox for the verification link. <a href='resend_verification.php'>Resend verification email</a>";
                } else {
                    // verified
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_name'] = $user['user_name'];
                    $_SESSION['email'] = $user['email'];
                    redirectTo('index.php');
                }
            } else {
                $error = "Invalid Password";
            }
        } else {
            $error = "No account found with that email";
        }
    }
}
