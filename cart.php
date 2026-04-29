<?php
session_start();
require_once "config/db.php";
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($qty < 1) $qty = 1;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$pid] = ['id'=>$product['id'],'name'=>$product['name'],'price'=>$product['price'],'image'=>$product['image'],'quantity'=>$qty];
        }
    }
    header("Location: cart.php"); exit;
}
if (isset($_GET['remove'])) { unset($_SESSION['cart'][(int)$_GET['remove']]); header("Location: cart.php"); exit; }
$total = 0;
foreach ($_SESSION['cart'] as $item) $total += $item['price'] * $item['quantity'];
$cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
?>
<!DOCTYPE html>
<html lang="mn"><head><meta charset="UTF-8">
<title>Сагс — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head>
<body>
<header class="topbar"><div class="inner">
<a href="index.php" class="logo">Urban<span>Wear</span></a>
<nav>
<a href="index.php">Home</a>
<?php if(isset($_SESSION['user'])): ?>
<a href="cart.php" class="active">🛒 Cart (<?= $cart_count ?>)</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Гарах</a>
<?php else: ?>
<a href="cart.php" class="active">🛒 Cart (<?= $cart_count ?>)</a>
<a href="login.php">Нэвтрэх</a>
<?php endif; ?>
</nav>
</div></header>
<div class="page-header"><div class="container">
<div class="breadcrumb"><a href="index.php">Home</a> › Сагс</div>
<h1>Таны Сагс</h1>
</div></div>
<section class="section"><div class="container">
<?php if (empty($_SESSION['cart'])): ?>
<div class="empty-state">
<div class="icon">🛒</div>
<h3>Сагс хоосон байна</h3>
<p>Та сагсандаа бүтээгдэхүүн нэмэгдүүлээгүй байна.</p>
<a href="index.php" class="btn btn-primary">← Дэлгүүр рүү очих</a>
</div>
<?php else: ?>
<?php foreach ($_SESSION['cart'] as $item): ?>
<div class="cart-item">
<img src="/online-shop/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
<div class="cart-item-info">
<h3><?= htmlspecialchars($item['name']) ?></h3>
<div class="qty">Тоо ширхэг: <?= $item['quantity'] ?></div>
</div>
<div class="cart-item-price"><?= number_format($item['price'] * $item['quantity']) ?>₮</div>
<a href="cart.php?remove=<?= $item['id'] ?>" class="cart-item-remove">✕ Хасах</a>
</div>
<?php endforeach; ?>
<div class="cart-total-box">
<div class="cart-total-row">
<span class="label">Нийт дүн</span>
<span class="amount"><?= number_format($total) ?>₮</span>
</div>
<a href="checkout.php" class="btn btn-primary" style="display:flex;justify-content:center;margin-top:20px;">Захиалга хийх →</a>
</div>
<?php endif; ?>
</div></section>
<footer><div class="footer-inner">
<div class="footer-logo">Urban<span>Wear</span></div>
<div class="footer-links"><a href="index.php">Home</a><a href="login.php">Login</a><a href="register.php">Register</a></div>
<div class="footer-copy">© 2026 UrbanWear.</div>
</div></footer>
</body></html>
