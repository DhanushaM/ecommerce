<?php
include('db.php');
session_start();

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: cart.php");
    exit();
}

// Update quantity
if (isset($_POST['update_qty'])) {
    foreach ($_POST['qty'] as $product_id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $qty;
        }
    }

    header("Location: cart.php");
    exit();
}

// Remove from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ccc; }
        h2 { text-align: center; }
        a, button { text-decoration: none; padding: 8px 16px; margin: 10px; display: inline-block; }
        .btn-back { background-color: #f0f0f0; border: 1px solid #ccc; }
        .btn-pay { background-color: green; color: white; border: none; }
        .btn-remove { color: red; text-decoration: none; }
    </style>
</head>
<body>

<h2>Your Shopping Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p style="text-align: center;">Your cart is empty.</p>
    <div style="text-align: center;">
        <a href="shop.php" class="btn-back">Back to Shop</a>
    </div>
<?php else: ?>

<form method="post" action="cart.php">
    <table>
        <tr>
            <th>Product</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Total (₹)</th>
            <th>Action</th>
        </tr>

        <?php
        $total = 0;

        foreach ($_SESSION['cart'] as $product_id => $qty) {
            $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td><?php echo number_format($product['price'], 2); ?></td>
            <td>
                <input type="number" name="qty[<?php echo $product_id; ?>]" value="<?php echo $qty; ?>" min="1" style="width: 60px;">
            </td>
            <td><?php echo number_format($subtotal, 2); ?></td>
            <td><a href="cart.php?remove=<?php echo $product_id; ?>" class="btn-remove">Remove</a></td>
        </tr>
        <?php
            }
        }
        ?>

        <tr>
            <td colspan="3"><strong>Total:</strong></td>
            <td colspan="2"><strong>₹<?php echo number_format($total, 2); ?></strong></td>
        </tr>
    </table>

    <div style="text-align: center;">
        <button type="submit" name="update_qty">Update Quantity</button>
        <a href="shop.php" class="btn-back">Back to Shop</a>
        <a href="payment.php" class="btn-pay">Proceed to Pay</a>
    </div>
</form>

<?php endif; ?>

</body>
</html>
