<?php

// ChatGPT wrote this sql query
$history_sql = "
  SELECT b.bid_amount, b.bid_time, u.user_name
    FROM bids AS b
    JOIN users AS u ON b.user_id = u.user_id
   WHERE b.item_id = ?
ORDER BY b.bid_time DESC
";
$history_stmt = mysqli_prepare($conn, $history_sql);
mysqli_stmt_bind_param($history_stmt, "i", $item_id);
mysqli_stmt_execute($history_stmt);
$history_result = mysqli_stmt_get_result($history_stmt);

echo "<h3>Bidding History</h3>";
if (mysqli_num_rows($history_result) > 0) {
    echo "<table> <tr>
            <th>User</th><th>Bid Amount</th><th>Time</th>
          </tr>";
    while ($row = mysqli_fetch_assoc($history_result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['user_name']) . "</td>
                <td>$" . number_format($row['bid_amount'], 2) . "</td>
                <td>" . htmlspecialchars($row['bid_time']) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No bids yet.</p>";
}