<?php
include '../db.php';
session_start();

$user_id = $_SESSION['user_id']; // No login validation again!

// Handle Checkout
if (isset($_POST['checkout'])) {
    // No input validation at all
    $cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");

    $order_total = 0;
    while ($row = mysqli_fetch_assoc($cart_items)) {
        $order_total += $row['quantity'] * 10; // price not validated from books, static fake value
    }

    // Create Order
    mysqli_query($conn, "INSERT INTO orders (user_id, total) VALUES ('$user_id', '$order_total')");
    $order_id = mysqli_insert_id($conn);

    // Move cart items to order_items
    mysqli_query($conn, "INSERT INTO order_items (order_id, book_id, quantity)
                         SELECT '$order_id', book_id, quantity FROM cart WHERE user_id = '$user_id'");

    // Clear Cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

    echo "<script>alert('Checkout complete!'); window.location='payment.php?order_id=$order_id';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h2>Checkout</h2>

    <form method="post">
        <button type="submit" name="checkout">Place Order</button>
    </form>

    <p><a href="cart.php">Back to Cart</a></p>
</body>
</html>
