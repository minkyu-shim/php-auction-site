<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch items uploaded by the logged-in user
$sql = "SELECT * FROM items WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Items</title>
    <link rel="stylesheet" href="/public/css/index_style.css">
</head>
<body>

<h2>My Uploaded Items</h2>

<a href="index.php">← Back to Home</a>
<br><br>

<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($item = mysqli_fetch_assoc($result)): ?>
        <div class="item-card">
            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
            <p><img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="" style="max-width:200px;"></p>
            <p><?php echo htmlspecialchars($item['description']); ?></p>
            <p><strong>Current Price:</strong> $<?php echo $item['current_price']; ?></p>
            <a href="../features/delete_item.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>You haven't uploaded any items yet.</p>
<?php endif; ?>

</body>
</html>
