<?php
session_start();
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auction Homepage</title>
    <link rel="stylesheet" href="/css/index_style.css">
</head>
<body>

<h1>Welcome to Minkyu's Auction Site</h1>

<!--The if statement below is written by ChatGPT-->
<!--It checks if the user is logged in or not. And displays the appropriate message.-->
<?php if (isset($_SESSION['user_name'])): ?>
    <p>
        Good to see you again, <?php echo $_SESSION['user_name']; ?> <br>
        <a href="upload_item.php">Upload Item</a> |
        <a href="logout.php">Logout</a>
    </p>
<?php else: ?>
    <p><a href="login.php">Login</a> | <a href="signup.php">Sign Up</a></p>
<?php endif; ?>


<h2>All Auction Items</h2>

<?php
$sql = "SELECT * FROM items ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='item-card'>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p><img src='" . htmlspecialchars($row['image_url']) . "'></p>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<p><strong>Current Price:</strong> $" . $row['current_price'] . "</p>";
        echo "<p><a href='bid.php?item_id=" . $row['id'] . "'>Place a Bid</a></p>";
        echo "</div>";
    }
} else {
    echo "<p>No items available.</p>";
}
?>

</body>
</html>
