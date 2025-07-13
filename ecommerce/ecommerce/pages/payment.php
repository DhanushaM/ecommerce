<?php
include('db.php');
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2 style='text-align:center;'>Your cart is empty.</h2>";
    echo "<p style='text-align:center;'><a href='shop.php'>Back to Shop</a></p>";
    exit();
}

// Calculate total
$total = 0;
$items = [];

foreach ($_SESSION['cart'] as $product_id => $qty) {
    $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
        $items[] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}

// Handle form submit
if (isset($_POST['pay_now'])) {
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $method = $_POST['payment_method'] ?? '';

    if ($name && $address && $method) {
        // ✅ Order placed (in real project you'd save to DB)
        echo "<h2 style='text-align:center; color: green;'>✅ Payment Successful!</h2>";
        echo "<p style='text-align:center;'>Thank you <strong>$name</strong> for your order.</p>";
        echo "<p style='text-align:center;'>Order will be shipped to: <em>$address</em></p>";
        echo "<p style='text-align:center;'><a href='shop.php'>Continue Shopping</a></p>";

        // 🔁 Clear cart
        unset($_SESSION['cart']);
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Please fill in all details.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        form { width: 50%; margin: 30px auto; padding: 20px; border: 1px solid #ccc; }
        input, textarea, select { width: 100%; padding: 8px; margin: 10px 0; }
        button { padding: 10px 20px; background-color: green; color: white; border: none; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>

<h2>🧾 Payment Summary</h2>

<table>
    <tr>
        <th>Product</th><th>Price</th><th>Quantity</th><th>Total</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['name']); ?></td>
        <td>₹<?= $item['price']; ?></td>
        <td><?= $item['qty']; ?></td>
        <td>₹<?= $item['subtotal']; ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Grand Total</strong></td>
        <td><strong>₹<?= $total; ?></strong></td>
    </tr>
</table>

<h3>💳 Enter Your Payment Details</h3>

<form method="post" action="payment.php">
    <label>Full Name</label>
    <input type="text" name="name" required>

    <label>Delivery Address</label>
    <textarea name="address" rows="3" required></textarea>

    <label>Payment Method</label>
    <select name="payment_method" required>
        <option value="">-- Select --</option>
        <option value="Cash on Delivery">Cash on Delivery</option>
        <option value="UPI">UPI</option>
        <option value="Card">Credit/Debit Card</option>
    </select>

    <button type="submit" name="pay_now">Pay Now</button>
</form>

</body>
</html>
