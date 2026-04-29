<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price       = (float)$_POST['price'];
    $image       = trim($_POST['image']);
    $description = trim($_POST['description']);
    if (empty($name) || $category_id <= 0 || $price <= 0) {
        $error = "Бүх шаардлагатай талбарыг бөглөнө үү";
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (category_id, name, price, image, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $name, $price, $image, $description]);
        header('Location: dashboard.php'); exit;
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Бүтээгдэхүүн нэмэх</title>
<link rel="stylesheet" href="/online-shop/css/style.css"></head>
<body><div class="form-box">
<h2>Бүтээгдэхүүн нэмэх</h2>
<?php if ($error): ?><p class="warning"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post">
<label>Нэр</label><input type="text" name="name" required>
<label>Ангилал</label>
<select name="category_id" required style="width:100%;padding:14px;margin:12px 0;border:1px solid #d1d5db;border-radius:12px;">
<?php foreach ($categories as $c): ?>
<option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
<?php endforeach; ?>
</select>
<label>Үнэ (₮)</label><input type="number" step="1000" name="price" required>
<label>Зураг</label><input type="text" name="image" placeholder="shirt1.jpg">
<label>Тайлбар</label>
<textarea name="description" rows="4" style="width:100%;padding:14px;border:1px solid #d1d5db;border-radius:12px;"></textarea>
<button type="submit">Хадгалах</button>
</form>
<br><a href="dashboard.php">← Буцах</a>
</div></body></html>
