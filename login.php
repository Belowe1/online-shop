<?php
session_start(); require_once "config/db.php"; $message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]); $password = trim($_POST["password"]);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]); $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION['user'] = $user['email']; $_SESSION['user_id'] = $user['id'];
        header("Location: profile.php"); exit;
    } else { $message = "Email эсвэл нууц үг буруу байна"; }
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
<label>Email</label><input type="email" name="email" placeholder="you@example.com" required>
<label>Нууц үг</label><input type="password" name="password" placeholder="••••••••" required>
<button type="submit">Нэвтрэх</button>
</form>
<p class="form-link">Бүртгэлгүй юу? <a href="register.php">Бүртгүүлэх</a></p>
</div></div></section>
<footer><div class="footer-inner"><div class="footer-logo">Urban<span>Wear</span></div><div class="footer-copy">© 2026 UrbanWear.</div></div></footer>
</body></html>
