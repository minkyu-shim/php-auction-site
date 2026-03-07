<?php
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/verification_check.php';

if (!isset($_SESSION['user_id'])) {
    redirectTo('login.php');
}

checkEmailVerification($conn);

if (isset($_GET['id'])) {
    $item_id = (int) $_GET['id'];
    $user_id = (int) $_SESSION['user_id'];

    $sql = "DELETE FROM items WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $item_id, $user_id);
    mysqli_stmt_execute($stmt);
}

redirectTo('my_items.php');
