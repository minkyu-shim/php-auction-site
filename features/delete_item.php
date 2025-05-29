<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Only allow deleting items owned by user
    $sql = "DELETE FROM items WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $item_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Item deleted successfully');</script>";
    }
}

// Go back to the user's item list
header("Location: my_items.php");
exit;
