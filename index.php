<?php
session_start();
require_once "config/db.php";
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$cat_icons = ['Гутал'=>'👟','Цамц'=>'👕','Өмд'=>'👖'];
?>
<!DOCTYPE html>
<html lang="mn">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css">
</head>
<body>
<header class="topbar"><div class="inner">
<a href="index.php" class="logo">Urban<span>Wear</span></a>
<nav>
<a href="index.php" class="active">Home</a>
<?php if(isset($_SESSION['user'])): ?>
<a href="cart.php">🛒 Cart</a><a href="profile.php">Profile</a><a href="logout.php">Гарах</a>
<?php else: ?>
<a href="register.php">Бүртгэл</a><a href="login.php">Нэвтрэх</a>
<?php endif; ?>
<a href="vulnerable_login.php" style="border:1px solid rgba(245,158,11,0.4);color:#F59E0B;">Security Lab</a>
</nav>
</div></header>
<section class="hero">
<div class="hero-bg"></div>
<div class="hero-content container">
<div class="hero-tag">2026 Collection</div>
<h1>New Drop<br><span>Just Hit</span></h1>
<p>Streetwear & Premium Fashion — Монголын хамгийн шинэ коллекц танд хүрлээ.</p>
<div class="hero-btns">
<a href="#shop" class="btn btn-primary">Shop Now →</a>
<a href="register.php" class="btn btn-outline">Join the Crew</a>
</div>
</div>
</section>
<div class="promo-strip"><div class="promo-strip-inner">
<span>Free Delivery</span><span>New Arrivals</span><span>Premium Quality</span><span>Fast Shipping</span>
<span>Free Delivery</span><span>New Arrivals</span><span>Premium Quality</span><span>Fast Shipping</span>
</div></div>
<section class="section" id="shop"><div class="container">
<div class="section-header">
<div class="section-tag">Browse</div>
<h2>Categories</h2>
<p>Өөрийн хэв маягт тохирсон ангиллыг сонгоорой</p>
</div>
<div class="category-grid">
<?php foreach ($categories as $c): $icon = $cat_icons[$c['name']] ?? '🛍'; ?>
<a class="category-card" href="category.php?id=<?= $c['id'] ?>">
<span class="cat-icon"><?= $icon ?></span>
<div class="cat-name"><?= htmlspecialchars($c['name']) ?></div>
<div class="cat-count">Collection →</div>
</a>
<?php endforeach; ?>
</div>
</div></section>
<footer><div class="footer-inner">
<div class="footer-logo">Urban<span>Wear</span></div>
<div class="footer-links"><a href="index.php">Home</a><a href="login.php">Login</a><a href="register.php">Register</a><a href="admin/login.php">Admin</a></div>
<div class="footer-copy">© 2026 UrbanWear.</div>
</div></footer>
</body></html>
