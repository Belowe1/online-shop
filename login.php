<?php
require_once "config/db.php"; session_start(); $message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify();
    $ip = $_SERVER['REMOTE_ADDR'];
    if (is_rate_limited($pdo, $ip)) {
        $message = "Та 5 удаа буруу оруулсан. 15 минут хүлээнэ үү.";
    } else {
        $email = trim($_POST["email"]); $password = trim($_POST["password"]);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]); $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user["password_hash"])) {
            clear_login_attempts($pdo, $ip);
            session_regenerate_id(true);
            $_SESSION['user'] = $user['email']; $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php"); exit;
        } else {
            record_failed_login($pdo, $ip);
            $message = "Email эсвэл нууц үг буруу байна";
        }
    }
}
?>
<!DOCTYPE html><html lang="mn"><head><meta charset="UTF-8">
<title>Нэвтрэх — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head><body>
<header class="topbar"><div class="inner"><a href="index.php" class="logo">Urban<span>Wear</span></a><nav><a href="index.php">Home</a></nav></div></header>
<section class="section"><div class="container"><div class="form-box">
<h2>Нэвтрэх</h2><p class="subtitle">Тавтай морил!</p>
<?php if ($message): ?><div class="message error"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
<label>Email</label><input type="email" name="email" placeholder="you@example.com" required>
<label>Нууц үг</label><input type="password" name="password" placeholder="••••••••" required>
<button type="submit">Нэвтрэх</button>
</form>
<p class="form-link">Бүртгэлгүй юу? <a href="register.php">Бүртгүүлэх</a></p>
</div></div></section>
<footer><div class="footer-inner"><div class="footer-logo">Urban<span>Wear</span></div><div class="footer-copy">© 2026 UrbanWear.</div></div></footer>
</body></html>
