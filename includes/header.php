<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store</title>
    <link rel="stylesheet" href="/online-shop/css/style.css">
</head>
<body>
<header>
    <div class="container nav">
        <h1><a href="/online-shop/index.php">🔥 Baagii Store</a></h1>
        <nav>
            <a href="/online-shop/index.php">Home</a>
            <a href="/online-shop/cart.php">Cart (<?php echo $cart_count; ?>)</a>
            <a href="/online-shop/admin/login.php">Admin</a>
        </nav>
    </div>
</header>
<main class="container">
