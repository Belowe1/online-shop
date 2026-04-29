<?php
require_once "config/db.php";

$message = "";
$query = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // LAB ONLY: intentionally vulnerable SQL query
    $query = "SELECT * FROM users WHERE email = '$email' AND password_hash = '$password'";

    try {
        $result = $pdo->query($query);
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $message = "Нэвтэрлээ - vulnerable login";
        } else {
            $message = "Email эсвэл password буруу";
        }
    } catch (Exception $e) {
        $message = "SQL error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vulnerable Login Lab</title>
    <link rel="stylesheet" href="/online-shop/css/style.css">
</head>
<body>

<h1>Vulnerable Login Lab</h1>
<a href="index.php">← Буцах</a>

<p><b>Анхаар:</b> Энэ login form зөвхөн lab/biy daalt demo зориулалттай.</p>

<p><?= htmlspecialchars($message) ?></p>

<?php if ($query): ?>
    <p><b>Query:</b></p>
    <pre><?= htmlspecialchars($query) ?></pre>
<?php endif; ?>

<form method="POST">
    <input type="text" name="email" placeholder="Email">
    <input type="text" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>

</body>
</html>
