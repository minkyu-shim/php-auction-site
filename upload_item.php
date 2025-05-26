<?php
session_start();
include "db.php";

// Check if the user is logged in, if not send to login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $starting_price = $_POST['starting_price'];
    $end_date = $_POST['end_date']; // only the date part

    // Check if the user has put everything ab items
    if (empty($title) || empty($description) || empty($image_url) || empty($starting_price) || empty($end_date)) {
        $error = "All fields are required";
    } else {
        $end_time = $end_date . ' 23:59:59'; // add default end time to the midnight

        // Insert to DB
        $sql = "INSERT INTO items (user_id, title, description, image_url, starting_price, current_price, end_time)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "isssdds",
            $_SESSION['user_id'],
            $title,
            $description,
            $image_url,
            $starting_price,
            $starting_price,
            $end_time
        );
        mysqli_stmt_execute($stmt);
        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Item</title>
</head>
<body>
<h1>Upload your Item to Sell</h1>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post" action="">
    <label>Title:</label><br>
    <input type="text" name="title"><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="4" cols="50"></textarea><br><br>

    <label>Image URL:</label><br>
    <input type="text" name="image_url"><br><br>

    <label>Starting Price:</label><br>
    <input type="number" step="0.01" name="starting_price"><br><br>

    <label>End Time:</label><br>
    <input type="date" name="end_date"><br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>
