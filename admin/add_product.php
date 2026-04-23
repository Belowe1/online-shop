<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = trim($_POST['image']);
    $description = trim($_POST['description']);

    $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock, image, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category, $price, $stock, $image, $description]);

    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="/online-shop/css/style.css">
</head>
<body>
    <div class="form-box">
        <h2>Add Product</h2>
        <form method="post">
            <label>Product Name</label>
            <input type="text" name="name" required>

            <label>Category</label>
            <select name="category" required>
                <option value="T-Shirt">T-Shirt</option>
                <option value="Hoodie">Hoodie</option>
                <option value="Jacket">Jacket</option>
                <option value="Pants">Pants</option>
                <option value="Shoes">Shoes</option>
            </select>

            <label>Price</label>
            <input type="number" step="0.01" name="price" required>

            <label>Stock</label>
            <input type="number" name="stock" required>

            <label>Image URL</label>
            <input type="text" name="image" placeholder="images/shirt1.jpg or https://...">

            <label>Description</label>
            <textarea name="description" rows="5"></textarea>

            <button type="submit" class="btn">Save Product</button>
        </form>
        <br>
        <a href="dashboard.php">Back</a>
    </div>
</body>
</html>
