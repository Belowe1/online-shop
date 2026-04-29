<?php
session_start();
require_once "config/db.php";
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['fullname'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment = trim($_POST['payment'] ?? '');
    if (empty($name) || empty($phone) || empty($address)) {
        $error = "Бүх талбарыг бөглөнө үү";
    } elseif (empty($_SESSION['cart'])) {
        $error = "Сагс хоосон байна";
    } else {
        $total = 0; $items = [];
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
            $items[] = $item['name'] . ' x' . $item['quantity'];
        }
        $items_str  = implode(', ', $items);
        $user_email = $_SESSION['user'] ?? 'guest';
        $stmt = $pdo->prepare("INSERT INTO orders (user_email, fullname, phone, address, payment, items, total) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$user_email, $name, $phone, $address, $payment, $items_str, $total]);
        $_SESSION['cart'] = [];
        header("Location: order_success.php"); exit;
    }
}
$total = 0;
$item_count = 0;
foreach ($_SESSION['cart'] ?? [] as $item) {
    $total += $item['price'] * $item['quantity'];
    $item_count += $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="mn"><head><meta charset="UTF-8">
<title>Захиалга — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head>
<body>
<header class="topbar"><div class="inner">
<a href="index.php" class="logo">Urban<span>Wear</span></a>
<nav><a href="index.php">Home</a><a href="cart.php">← Сагс (<?= $item_count ?>)</a></nav>
</div></header>
<div class="page-header"><div class="container">
<div class="breadcrumb"><a href="index.php">Home</a> › <a href="cart.php">Сагс</a> › Захиалга</div>
<h1>Захиалга Хийх</h1>
</div></div>
<section class="section"><div class="container">
<div class="form-box">
<h2>Хүргэлтийн мэдээлэл</h2>
<p class="subtitle">Нийт: <strong style="color:var(--purple-light)"><?= number_format($total) ?>₮</strong> — <?= $item_count ?> бараа</p>
<?php if ($error): ?><div class="message error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="POST">
<label>Овог нэр</label>
<input type="text" name="fullname" placeholder="Таны бүтэн нэр" required>
<label>Утасны дугаар</label>
<input type="text" name="phone" placeholder="+976 xxxxxxxx" required>
<label>Хүргэлтийн хаяг</label>
<input type="text" name="address" placeholder="Дүүрэг, хороо, байр, тоот" required>
<label>Төлбөрийн арга</label>
<select name="payment">
<option value="QPay">QPay</option>
<option value="Карт">Банкны карт</option>
<option value="Бэлэн">Бэлэн мөнгө</option>
</select>
<button type="submit">✓ Захиалга баталгаажуулах</button>
</form>
</div>
</div></section>
<footer><div class="footer-inner">
<div class="footer-logo">Urban<span>Wear</span></div>
<div class="footer-copy">© 2026 UrbanWear.</div>
</div></footer>
</body></html>
