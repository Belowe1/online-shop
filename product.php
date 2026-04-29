<?php
session_start();
require_once 'config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php"); exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = max(1, (int)($_POST['quantity'] ?? 1));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id] = [
            'id'       => $product['id'],
            'name'     => $product['name'],
            'price'    => $product['price'],
            'image'    => $product['image'],
            'quantity' => $quantity
        ];
    }
    header('Location: cart.php'); exit;
}

$cart_count = 0;
if (!empty($_SESSION['cart'])) foreach ($_SESSION['cart'] as $item) $cart_count += $item['quantity'];
?>
<!DOCTYPE html>
<html lang="mn"><head><meta charset="UTF-8">
<title><?= htmlspecialchars($product['name']) ?> — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head>
<body>
<header class="topbar"><div class="inner">
<a href="index.php" class="logo">Urban<span>Wear</span></a>
<nav>
<a href="index.php">Home</a>
<?php if(isset($_SESSION['user'])): ?>
<a href="cart.php">🛒 Cart (<?= $cart_count ?>)</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Гарах</a>
<?php else: ?>
<a href="cart.php">🛒 Cart (<?= $cart_count ?>)</a>
<a href="login.php">Нэвтрэх</a>
<?php endif; ?>
</nav>
</div></header>
<div class="page-header"><div class="container">
<div class="breadcrumb">
<a href="index.php">Home</a> ›
<a href="index.php#shop"><?= htmlspecialchars($product['category_name'] ?? 'Бүтээгдэхүүн') ?></a> ›
<?= htmlspecialchars($product['name']) ?>
</div>
<h1><?= htmlspecialchars($product['name']) ?></h1>
</div></div>
<section class="section"><div class="container">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;max-width:960px;margin:auto;">
<div>
<img src="/online-shop/images/<?= htmlspecialchars($product['image']) ?>"
     alt="<?= htmlspecialchars($product['name']) ?>"
     style="width:100%;border-radius:20px;object-fit:cover;aspect-ratio:1/1;background:var(--dark2);">
</div>
<div>
<div class="hero-tag" style="margin-bottom:16px;"><?= htmlspecialchars($product['category_name'] ?? '') ?></div>
<h2 style="margin-bottom:16px;"><?= htmlspecialchars($product['name']) ?></h2>
<p style="color:var(--gray);line-height:1.8;margin-bottom:28px;"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
<div class="price" style="font-size:46px;margin-bottom:28px;"><?= number_format($product['price']) ?>₮</div>
<form method="post" style="display:flex;gap:12px;align-items:stretch;flex-wrap:wrap;">
<input type="number" name="quantity" value="1" min="1" max="99"
       style="width:72px;padding:14px;background:var(--dark3);border:1px solid rgba(255,255,255,0.1);border-radius:10px;color:var(--white);font-size:16px;text-align:center;outline:none;">
<button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;font-size:16px;">
  🛒 Сагсанд нэмэх
</button>
</form>
<p style="margin-top:20px;font-size:13px;color:var(--gray);">
  <a href="index.php" style="color:var(--purple-light);text-decoration:none;">← Дэлгүүр рүү буцах</a>
</p>
</div>
</div>
</div></section>
<footer><div class="footer-inner">
<div class="footer-logo">Urban<span>Wear</span></div>
<div class="footer-links"><a href="index.php">Home</a><a href="login.php">Login</a><a href="register.php">Register</a></div>
<div class="footer-copy">© 2026 UrbanWear.</div>
</div></footer>
</body></html>
