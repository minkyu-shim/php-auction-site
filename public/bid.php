<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/verification_check.php';

// Check user is logged in to bid
if(!isset($_SESSION['user_id'])) {
    redirectTo('login.php');
}

// Check if email is verified
checkEmailVerification($conn);

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

$auction_has_ended = strtotime($item['end_time']) <= time();
$next_min_bid = number_format(((float) $item['current_price']) + 0.01, 2, '.', '');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bid_amount = $_POST['bid_amount'];

    if ($auction_has_ended) {
        $error = "This auction has already ended.";
    } else if ($bid_amount <= $item['current_price']) {
        $error = "Bid amount must be higher than the current price.";
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
        redirectTo('index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Place a Bid</title>
    <link rel="stylesheet" href="<?php echo appUrl('css/bid_style.css'); ?>">
</head>
<body>
<h1><?php echo htmlspecialchars($item['title']); ?></h1>

<p><img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="300"></p>
<p><?php echo htmlspecialchars($item['description']); ?></p>
<p><strong>Current Price:</strong> $<?php echo $item['current_price']; ?></p>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<p><strong>Auction ends at:</strong> <?php echo $item['end_time']; ?></p>
<p id="countdown" style="font-weight: bold;"></p>

<form method="post">
    <label>Your Bid:</label><br>
    <input type="number" name="bid_amount" step="0.01" min="<?php echo htmlspecialchars($next_min_bid); ?>" required <?php echo $auction_has_ended ? 'disabled' : ''; ?>><br><br>
    <button type="submit" <?php echo $auction_has_ended ? 'disabled' : ''; ?>>Place Bid</button>
    <!--Git Branch Feature Add Winning Status-->
    <?php require __DIR__ . '/../features/winning_status.php'; ?>
</form>

<!-- Git Branch Feature Add Bidding History -->
<?php require __DIR__ . '/../features/bidding_history.php'; ?>

<p><a href="<?php echo appUrl('index.php'); ?>">Back to Home</a></p>

<script src="<?php echo appUrl('js/bidding_countdown.js'); ?>"></script>
<script>
setupBiddingCountdown(<?php echo json_encode($item['end_time']); ?>);
</script>
</body>
</html>
