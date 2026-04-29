<?php
session_start();
require_once "config/db.php";
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$cart_count = 0;
if (!empty($_SESSION['cart'])) foreach ($_SESSION['cart'] as $item) $cart_count += $item['quantity'];

$orders = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_email=? ORDER BY id DESC");
    $stmt->execute([$_SESSION['user']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="mn"><head><meta charset="UTF-8">
<title>Профайл — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head>
<body>
<header class="topbar"><div class="inner">
<a href="index.php" class="logo">Urban<span>Wear</span></a>
<nav>
<a href="index.php">Home</a>
<a href="cart.php">🛒 Cart (<?= $cart_count ?>)</a>
<a href="profile.php" class="active">Profile</a>
<a href="logout.php">Гарах</a>
</nav>
</div></header>
<div class="page-header"><div class="container">
<h1>Миний Профайл</h1>
</div></div>
<section class="section"><div class="container" style="max-width:800px;">

<div class="form-box" style="margin-bottom:32px;">
<div style="display:flex;align-items:center;gap:20px;margin-bottom:28px;">
<div style="width:72px;height:72px;border-radius:50%;background:rgba(124,58,237,0.3);border:2px solid var(--purple);display:flex;align-items:center;justify-content:center;font-size:32px;flex-shrink:0;">👤</div>
<div>
<h2 style="font-size:22px;margin-bottom:4px;"><?= htmlspecialchars($user['email']) ?></h2>
<p style="color:var(--gray);font-size:13px;">Бүртгэгдсэн огноо: <?= $user['created_at'] ?></p>
</div>
</div>
<div style="display:flex;gap:12px;flex-wrap:wrap;">
<a href="cart.php" class="btn btn-primary" style="flex:1;justify-content:center;">🛒 Сагс харах (<?= $cart_count ?>)</a>
<a href="logout.php" class="btn btn-outline" style="flex:1;justify-content:center;">Гарах</a>
</div>
</div>

<?php if (!empty($orders)): ?>
<div class="section-header" style="margin-bottom:20px;">
<div class="section-tag">History</div>
<h2>Захиалгын түүх</h2>
</div>
<table class="table">
<thead><tr>
<th>#</th><th>Барааны нэр</th><th>Нийт дүн</th><th>Хаяг</th><th>Төлбөр</th>
</tr></thead>
<tbody>
<?php foreach ($orders as $o): ?>
<tr>
<td style="color:var(--gray);"><?= $o['id'] ?></td>
<td><?= htmlspecialchars($o['items']) ?></td>
<td><span class="price" style="font-size:20px;"><?= number_format($o['total']) ?>₮</span></td>
<td style="color:var(--gray);"><?= htmlspecialchars($o['address']) ?></td>
<td><?= htmlspecialchars($o['payment']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php elseif (isset($_SESSION['user'])): ?>
<div class="empty-state" style="padding:48px 24px;">
<div class="icon">📦</div>
<h3>Захиалга байхгүй</h3>
<p>Та одоогоор захиалга хийгээгүй байна.</p>
<a href="index.php" class="btn btn-primary">Дэлгүүр рүү очих</a>
</div>
<?php endif; ?>

</div></section>
<footer><div class="footer-inner">
<div class="footer-logo">Urban<span>Wear</span></div>
<div class="footer-links"><a href="index.php">Home</a><a href="cart.php">Cart</a><a href="logout.php">Logout</a></div>
<div class="footer-copy">© 2026 UrbanWear.</div>
</div></footer>
</body></html>
