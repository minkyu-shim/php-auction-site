<?php
// winning_status.php
// expects $conn, $item_id, $user_id to already be in scope

$top_sql = "
  SELECT b.user_id, u.user_name
    FROM bids AS b
    JOIN users AS u ON b.user_id = u.user_id
   WHERE b.item_id = ?
ORDER BY b.bid_amount DESC, b.bid_time DESC
   LIMIT 1
";
$top_stmt = mysqli_prepare($conn, $top_sql);
mysqli_stmt_bind_param($top_stmt, "i", $item_id);
mysqli_stmt_execute($top_stmt);
$top_res = mysqli_stmt_get_result($top_stmt);

if ($top_row = mysqli_fetch_assoc($top_res)) {
    if ($top_row['user_id'] == $user_id) {
        echo "<p style='color:green;'>You’re currently the highest bidder.</p>";
    } else {
        echo "<p style='color:red;'>You’ve been outbid. Highest bidder: "
            . htmlspecialchars($top_row['user_name'])
            . ".</p>";
    }
}