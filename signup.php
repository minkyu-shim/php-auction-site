<?php
session_start();
include 'db.php';

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

            // Creating a new user in my db
            $sql = "INSERT INTO users (user_name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $user_name, $email, $hashed_password);

            if(mysqli_stmt_execute($stmt)){
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $user_name;
                $_SESSION['email'] = $email;
                header("Location: index.php");
                exit;
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
    <title>Login</title>
</head>
<body>
<h2>Signup</h2>

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
</body>
</html>
