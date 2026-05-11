<?php
require_once '../config/db.php'; session_start();
if (isset($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $ip = $_SERVER['REMOTE_ADDR'];
    if (is_rate_limited($pdo, $ip)) {
        $error = 'Та 5 удаа буруу оруулсан. 15 минут хүлээнэ үү.';
    } else {
        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($password, $admin['password_hash'])) {
            clear_login_attempts($pdo, $ip);
            session_regenerate_id(true);
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['email'];
            header('Location: dashboard.php'); exit;
        } else {
            record_failed_login($pdo, $ip);
            $error = 'Email эсвэл нууц үг буруу байна';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — UrbanWear</title>
    <link rel="stylesheet" href="/online-shop/css/style.css">
</head>
<body>
<header class="topbar"><div class="inner">
<a href="/online-shop/index.php" class="logo">Urban<span>Wear</span></a>
</div></header>
<section class="section"><div class="container"><div class="form-box">
<h2>Admin Нэвтрэх</h2>
<p class="subtitle">Зөвхөн админд зориулсан хэсэг</p>
<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <label>Email</label>
    <input type="email" name="email" placeholder="admin@example.com" required>
    <label>Нууц үг</label>
    <input type="password" name="password" placeholder="••••••••" required>
    <button type="submit">Нэвтрэх</button>
</form>
<p class="form-link"><a href="/online-shop/index.php">← Дэлгүүр рүү буцах</a></p>
</div></div></section>
<footer><div class="footer-inner"><div class="footer-logo">Urban<span>Wear</span></div><div class="footer-copy">© 2026 UrbanWear Admin.</div></div></footer>
</body>
</html>
