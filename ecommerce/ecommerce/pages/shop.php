<?php
include('db.php');
session_start();

// Fetch all products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <style>
        .product { 
            border: 1px solid #ccc; 
            padding: 10px; 
            margin: 10px; 
            width: 220px; 
            display: inline-block; 
            vertical-align: top;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .product h3 { margin: 0 0 10px 0; }
        .product p { margin: 5px 0; }
        form { margin-top: 10px; }
        input[type=number] { width: 50px; }
        button { padding: 6px 12px; }
    </style>
</head>
<body>

<h2 style="text-align: center;">🛍️ Welcome to the Shop</h2>

<div style="text-align: center; margin-bottom: 20px;">
    <a href="cart.php">🛒 View Cart</a>
</div>

<hr>

<div style="text-align: center;">
<?php foreach ($products as $product): ?>
    <div class="product">
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p>Price: ₹<?php echo number_format($product['price'], 2); ?></p>
        <form method="post" action="cart.php">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <label>Qty:</label>
            <input type="number" name="quantity" value="1" min="1">
            <br><br>
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>
