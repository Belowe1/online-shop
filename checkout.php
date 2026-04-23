<?php
require_once 'config/db.php';
include 'includes/header.php';

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($customer_name && $phone && $address) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, address, total_price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$customer_name, $phone, $address, $total]);
            $order_id = $pdo->lastInsertId();

            $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stock_stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

            foreach ($_SESSION['cart'] as $item) {
                $item_stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
                $stock_stmt->execute([$item['quantity'], $item['id'], $item['quantity']]);
            }

            $pdo->commit();
            $_SESSION['cart'] = [];
            header('Location: order_success.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Order failed: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>

<div class="form-box">
    <h2>Checkout</h2>
    <br>
    <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
    <br>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Full Name</label>
        <input type="text" name="customer_name" required>

        <label>Phone</label>
        <input type="text" name="phone" required>

        <label>Address</label>
        <textarea name="address" rows="4" required></textarea>

        <button type="submit" class="btn">Place Order</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
