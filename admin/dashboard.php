<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/online-shop/css/style.css">
</head>
<body>
    <div class="container" style="padding:30px 0;">
        <h2>Admin Dashboard</h2>
        <br>
        <p>
            Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> |
            <a href="add_product.php">Add Product</a> |
            <a href="logout.php">Logout</a>
        </p>
        <br>

        <table class="table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>$<?php echo $product['price']; ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <a class="btn" href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
