<?php
require_once 'config/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="margin-bottom:20px;">Latest Clothes</h2>
<div style="background: url('https://images.unsplash.com/photo-1523381210434-271e8be1f52b') center/cover; height:300px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; justify-content:center; color:white;">
    <h1 style="background: rgba(0,0,0,0.5); padding:20px; border-radius:10px;">
        New Fashion Collection 🔥
    </h1>
</div>
<div class="products">
    <?php foreach ($products as $product): ?>
        <div class="card">
            <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/400x300?text=No+Image'; ?>" alt="product">
            <div class="card-body">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
                <p class="price">$<?php echo $product['price']; ?></p>
                <p><?php echo htmlspecialchars(substr($product['description'], 0, 80)); ?>...</p>
                <br>
                <a class="btn" href="product.php?id=<?php echo $product['id']; ?>">View</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
