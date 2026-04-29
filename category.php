<?php
session_start();
require_once "config/db.php";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]); $category = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$category) { header("Location: index.php"); exit; }
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt->execute([$id]); $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html><html lang="mn"><head><meta charset="UTF-8">
<title><?= htmlspecialchars($category['name']) ?> — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head><body>
<header class="topbar"><div class="inner">
<a href="index.php" class="logo">Urban<span>Wear</span></a>
<nav><a href="index.php">Home</a><a href="cart.php">🛒 Cart</a></nav>
</div></header>
<div class="page-header"><div class="container">
<div class="breadcrumb"><a href="index.php">Home</a> › <?= htmlspecialchars($category['name']) ?></div>
<h1><?= htmlspecialchars($category['name']) ?></h1>
</div></div>
<section class="section"><div class="container">
<?php if (empty($products)): ?>
<div class="empty-state"><div class="icon">📦</div><h3>Бүтээгдэхүүн алга</h3><p>Энэ ангилалд одоогоор бүтээгдэхүүн байхгүй.</p><a href="index.php" class="btn btn-primary">Буцах</a></div>
<?php else: ?>
<div class="product-grid">
<?php foreach ($products as $p): ?>
<div class="product-card">
<span class="product-badge">New</span>
<img src="/online-shop/images/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
<div class="product-info">
<h3><?= htmlspecialchars($p['name']) ?></h3>
<p><?= htmlspecialchars($p['description']) ?></p>
<div class="product-footer">
<span class="price"><?= number_format($p['price']) ?>₮</span>
<form method="POST" action="cart.php" style="flex:1;">
<input type="hidden" name="product_id" value="<?= $p['id'] ?>">
<input type="hidden" name="quantity" value="1">
<button type="submit" class="add-btn">+ Сагсанд</button>
</form>
</div>
</div></div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div></section>
<footer><div class="footer-inner"><div class="footer-logo">Urban<span>Wear</span></div><div class="footer-copy">© 2026 UrbanWear.</div></div></footer>
</body></html>
