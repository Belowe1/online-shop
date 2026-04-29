<?php
require_once "config/db.php"; $message = ""; $msg_type = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]); $password = trim($_POST["password"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $message="Email буруу"; $msg_type="error"; }
    elseif (strlen($password) < 8) { $message="8+ тэмдэгт оруулна уу"; $msg_type="error"; }
    elseif (!preg_match("/[A-Z]/", $password)) { $message="Том үсэг байх ёстой"; $msg_type="error"; }
    elseif (!preg_match("/[0-9]/", $password)) { $message="Тоо байх ёстой"; $msg_type="error"; }
    else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
        $stmt->execute([$email, $hash]);
        $message="Бүртгэл амжилттай!"; $msg_type="success";
    }
}
?>
<!DOCTYPE html><html lang="mn"><head><meta charset="UTF-8">
<title>Бүртгэл — UrbanWear</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head><body>
<header class="topbar"><div class="inner"><a href="index.php" class="logo">Urban<span>Wear</span></a><nav><a href="login.php">Нэвтрэх</a></nav></div></header>
<section class="section"><div class="container"><div class="form-box">
<h2>Бүртгүүлэх</h2><p class="subtitle">Crew-д нэгдэж онцгой санал авааарай</p>
<?php if ($message): ?><div class="message <?= $msg_type ?>"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<form method="POST">
<label>Email</label><input type="email" name="email" placeholder="you@example.com" required>
<label>Нууц үг</label><input type="password" name="password" placeholder="Хамгийн багадаа 8 тэмдэгт" required>
<button type="submit">Бүртгүүлэх</button>
</form>
<p class="form-link">Бүртгэлтэй юу? <a href="login.php">Нэвтрэх</a></p>
</div></div></section>
<footer><div class="footer-inner"><div class="footer-logo">Urban<span>Wear</span></div><div class="footer-copy">© 2026 UrbanWear.</div></div></footer>
</body></html>
