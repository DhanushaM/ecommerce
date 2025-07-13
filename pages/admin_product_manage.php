<?php
include('db.php');
session_start();

// Only admin can access
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Add product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->execute([$name, $price]);
    header("Location: admin_manage_products.php");
    exit();
}

// Delete product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_manage_products.php");
    exit();
}

// Fetch products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Manage Products</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        form { margin: 20px auto; text-align: center; }
        input { padding: 5px; margin: 5px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Manage Products</h2>
<p style="text-align:center;"><a href="admin_dashboard.php">Back to Dashboard</a> | <a href="adminlogout.php">Logout</a></p>

<!-- Add Product Form -->
<form method="post">
    <h3>Add New Product</h3>
    <input type="text" name="name" placeholder="Product Name" required>
    <input type="number" step="0.01" name="price" placeholder="Price ₹" required>
    <button type="submit" name="add_product">Add Product</button>
</form>

<!-- Products Table -->
<table>
    <tr>
        <th>ID</th><th>Name</th><th>Price (₹)</th><th>Action</th>
    </tr>
    <?php foreach ($products as $product): ?>
    <tr>
        <td><?= $product['id']; ?></td>
        <td><?= htmlspecialchars($product['name']); ?></td>
        <td><?= number_format($product['price'], 2); ?></td>
        <td>
            <a href="admin_manage_products.php?delete=<?= $product['id']; ?>" onclick="return confirm('Delete this product?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
