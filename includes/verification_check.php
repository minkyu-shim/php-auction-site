<?php
/**
 * Verification Check Helper
 * Checks if logged-in user has verified their email
 * Include this file in pages that require email verification
 */

require_once __DIR__ . '/config.php';

// This function should be called after session_start() and db connection
function checkEmailVerification($conn) {
    // Check if user is logged in
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Check if user's email is verified
        $sql = "SELECT email_verified FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($user = mysqli_fetch_assoc($result)) {
            if($user['email_verified'] == 0) {
                // User is logged in but email not verified
                // Clear session and redirect to verification reminder page
                session_destroy();
                redirectTo('verification_required.php');
            }
        }
    }
}
