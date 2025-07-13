<?php
session_start();
include '../includes/db.php'; // DB connection

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = '../images/' . $image;

    if (move_uploaded_file($image_tmp, $image_folder)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $price, $description, $image]);
        $success_message = "Product added successfully!";
    } else {
        $error_message = "Failed to upload image!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            padding: 30px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .message {
            text-align: center;
            color: green;
            margin: 10px 0;
        }
        .error {
            text-align: center;
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Product</h2>
    <?php if (isset($success_message)) echo '<p class="message">' . htmlspecialchars($success_message) . '</p>'; ?>
    <?php if (isset($error_message)) echo '<p class="error">' . htmlspecialchars($error_message) . '</p>'; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Product Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>
</div>

</body>
</html>
