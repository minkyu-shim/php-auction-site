<?php
session_start();
include "db.php";

// Check user is logged in to bid
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check the item ID
if(!isset($_GET['item_id'])) {
    echo "Item ID not set";
    exit;
}

$item_id = $_GET['item_id'];
$user_id = $_SESSION['user_id'];

// Get item info from db
$sql = "SELECT * FROM items WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $item_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

if(!$item) {
    echo "Item not found";
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bid_amount = $_POST['bid_amount'];

    if ($bid_amount <= $item['current_price']) {
        echo "Bid amount must be higher than current price";
    } else {
        // Insert the new info to bid table
        $insert_bid_sql = "insert into bids (item_id, user_id, bid_amount) values (?, ?, ?)";
        $insert_bid_stmt = mysqli_prepare($conn, $insert_bid_sql);
        mysqli_stmt_bind_param($insert_bid_stmt, "iid", $item_id, $_SESSION['user_id'], $bid_amount);
        mysqli_stmt_execute($insert_bid_stmt);

        // Update the new price at items table as well
        $update_item_sql = "update items set current_price = ? where id = ?";
        $update_item_stmt = mysqli_prepare($conn, $update_item_sql);
        mysqli_stmt_bind_param($update_item_stmt, "di", $bid_amount, $item_id);
        mysqli_stmt_execute($update_item_stmt);

        // Send users back to the index page
        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Place a Bid</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<h1><?php echo htmlspecialchars($item['title']); ?></h1>

<p><img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="300"></p>
<p><?php echo htmlspecialchars($item['description']); ?></p>
<p><strong>Current Price:</strong> $<?php echo $item['current_price']; ?></p>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    <label>Your Bid:</label><br>
    <input type="number" name="bid_amount" step="0.01" required><br><br>
    <button type="submit">Place Bid</button>
</form>
<!--Git Branch Feature Add Winning Status-->
<?php include 'features/winning_status.php'; ?>

<!-- Git Branch Feature Add Bidding History -->
<?php include 'features/bidding_history.php'; ?>

<p><a href="index.php">Back to Home</a></p>
</body>
</html>

