<?php
session_start();
include '../db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data from session
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Home</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h1>Welcome back, <?php echo htmlspecialchars($username); ?>!</h1>

<nav>
    <ul>
        <li><a href="explore.php">📚 Explore Books</a></li>
        <li><a href="profile.php">👤 My Profile</a></li>
        <li><a href="cart.php">🛒 View Cart</a></li>
        <li><a href="client_orders.php">🧾 Order History</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
</nav>

</body>
</html>
