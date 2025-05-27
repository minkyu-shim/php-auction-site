<?php

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
                // verified
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['email'] = $user['email'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid Password";
            }
        } else {
            $error = "No account found with that email";
        }
    }
}