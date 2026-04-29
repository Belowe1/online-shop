<?php
session_start(); require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$products = $pdo->query("SELECT p.*,c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$total_revenue = array_sum(array_column($orders, 'total'));
?>
<!DOCTYPE html><html lang="mn"><head><meta charset="UTF-8">
<title>Admin — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head><body>
<header class="topbar"><div class="inner">
<a href="/online-shop/index.php" class="logo">Urban<span>Wear</span></a>
<nav><span style="color:var(--gray);font-size:14px;">Admin: <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
<a href="add_product.php" class="active">+ Бүтээгдэхүүн</a><a href="logout.php">Гарах</a></nav>
</div></header>
<section class="section"><div class="container">
<h2 style="margin-bottom:32px;">Dashboard</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:40px;">
<div style="background:var(--dark2);border-radius:14px;padding:24px;border:1px solid rgba(124,58,237,0.2);">
<div style="font-size:12px;color:var(--gray);letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Бүтээгдэхүүн</div>
<div style="font-family:'Bebas Neue',sans-serif;font-size:40px;color:var(--purple-light);"><?= count($products) ?></div></div>
<div style="background:var(--dark2);border-radius:14px;padding:24px;border:1px solid rgba(124,58,237,0.2);">
<div style="font-size:12px;color:var(--gray);letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Захиалга</div>
<div style="font-family:'Bebas Neue',sans-serif;font-size:40px;color:var(--purple-light);"><?= count($orders) ?></div></div>
<div style="background:var(--dark2);border-radius:14px;padding:24px;border:1px solid rgba(124,58,237,0.2);">
<div style="font-size:12px;color:var(--gray);letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Хэрэглэгч</div>
<div style="font-family:'Bebas Neue',sans-serif;font-size:40px;color:var(--purple-light);"><?= count($users) ?></div></div>
<div style="background:var(--dark2);border-radius:14px;padding:24px;border:1px solid rgba(16,185,129,0.2);">
<div style="font-size:12px;color:var(--gray);letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Нийт орлого</div>
<div style="font-family:'Bebas Neue',sans-serif;font-size:28px;color:#34D399;"><?= number_format($total_revenue) ?>₮</div></div>
</div>
<div class="tabs">
<button class="tab-btn active" onclick="showTab('products',this)">🛍 Бүтээгдэхүүн (<?= count($products) ?>)</button>
<button class="tab-btn" onclick="showTab('orders',this)">📦 Захиалга (<?= count($orders) ?>)</button>
<button class="tab-btn" onclick="showTab('users',this)">👤 Хэрэглэгч (<?= count($users) ?>)</button>
</div>
<div id="products" style="display:block;">
<table class="table">
<tr><th>ID</th><th>Нэр</th><th>Ангилал</th><th>Үнэ</th><th>Устгах</th></tr>
<?php foreach ($products as $p): ?>
<tr><td>#<?= $p['id'] ?></td><td><?= htmlspecialchars($p['name']) ?></td><td><?= htmlspecialchars($p['cat_name']??'-') ?></td>
<td style="color:var(--purple-light);font-weight:600;"><?= number_format($p['price']) ?>₮</td>
<td><a href="delete_product.php?id=<?= $p['id'] ?>" style="color:#FCA5A5;font-size:13px;" onclick="return confirm('Устгах уу?')">Устгах</a></td></tr>
<?php endforeach; ?>
</table></div>
<div id="orders" style="display:none;">
<table class="table">
<tr><th>ID</th><th>Нэр</th><th>Утас</th><th>Бараа</th><th>Нийт</th><th>Төлбөр</th><th>Огноо</th></tr>
<?php foreach ($orders as $o): ?>
<tr><td>#<?= $o['id'] ?></td><td><?= htmlspecialchars($o['fullname']) ?></td><td><?= htmlspecialchars($o['phone']) ?></td>
<td style="font-size:12px;color:var(--gray);"><?= htmlspecialchars($o['items']) ?></td>
<td style="color:var(--purple-light);font-weight:600;"><?= number_format($o['total']) ?>₮</td>
<td><?= htmlspecialchars($o['payment']) ?></td>
<td style="font-size:12px;color:var(--gray);"><?= date('Y.m.d',strtotime($o['created_at'])) ?></td></tr>
<?php endforeach; ?>
</table></div>
<div id="users" style="display:none;">
<table class="table">
<tr><th>ID</th><th>Email</th><th>Огноо</th></tr>
<?php foreach ($users as $u): ?>
<tr><td>#<?= $u['id'] ?></td><td><?= htmlspecialchars($u['email']) ?></td><td style="color:var(--gray);font-size:13px;"><?= $u['created_at'] ?></td></tr>
<?php endforeach; ?>
</table></div>
</div></section>
<footer><div class="footer-inner"><div class="footer-logo">Urban<span>Wear</span></div><div class="footer-copy">© 2026 UrbanWear Admin.</div></div></footer>
<script>
function showTab(name,btn){
document.querySelectorAll('#products,#orders,#users').forEach(t=>t.style.display='none');
document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
document.getElementById(name).style.display='block';btn.classList.add('active');}
</script>
</body></html>
