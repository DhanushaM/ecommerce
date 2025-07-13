<?php
include('db.php');
session_start();

// Only admin can access
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

$message = '';

// Add product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->execute([$name, $price]);

    $message = "✅ Product added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Add Product</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 50px; }
        form { display: inline-block; padding: 20px; border: 1px solid #ccc; }
        input { display: block; margin: 10px auto; padding: 8px; width: 250px; }
        button { padding: 10px 20px; }
        .success { color: green; }
    </style>
</head>
<body>

<h2>Admin - Add Product</h2>
<p><a href="admin_dashboard.php">Back to Dashboard</a> | <a href="adminlogout.php">Logout</a></p>

<?php if ($message): ?>
    <p class="success"><?= $message ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="name" placeholder="Product Name" required>
    <input type="number" step="0.01" name="price" placeholder="Price ₹" required>
    <button type="submit" name="add_product">Add Product</button>
</form>

</body>
</html>
