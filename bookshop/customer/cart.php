<?php
include '../db.php';
session_start();

$user_id = $_SESSION['user_id']; // No validation if not logged in

// Delete cart item (no CSRF token, no ownership check!)
if (isset($_GET['delete'])) {
    $cart_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$cart_id'");
    echo "<script>alert('Item removed!'); window.location='cart.php';</script>";
}

// Fetch user's cart
$cart_items = mysqli_query($conn, "SELECT cart.*, books.title, books.price 
                                   FROM cart 
                                   JOIN books ON cart.book_id = books.id 
                                   WHERE cart.user_id = '$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h2>Your Cart</h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Title</th>
            <th>Quantity</th>
            <th>Price (Each)</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>

        <?php
        $total = 0;
        while ($row = mysqli_fetch_assoc($cart_items)) {
            $subtotal = $row['price'] * $row['quantity'];
            $total += $subtotal;

            echo "<tr>";
            echo "<td>".$row['title']."</td>"; // vulnerable to XSS
            echo "<td>".$row['quantity']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>$".$subtotal."</td>";
            echo "<td><a href='cart.php?delete=".$row['id']."'>Remove</a></td>"; // CSRF vulnerable
            echo "</tr>";
        }
        ?>
    </table>

    <h3>Total: $<?php echo $total; ?></h3>

    <form action="checkout.php" method="post">
        <button type="submit" name="checkout">Proceed to Checkout</button>
    </form>

    <p><a href="explore.php">Continue Shopping</a></p>
</body>
</html>
