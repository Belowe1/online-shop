<?php
include 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header('Location: cart.php');
    exit;
}

$total = 0;
?>

<h2 style="margin-bottom:20px;">Your Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="message">Your cart is empty.</div>
<?php else: ?>
    <table class="table">
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
        <tr>
            <td><img src="<?php echo !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/80'; ?>" width="80"></td>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td>$<?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($subtotal, 2); ?></td>
            <td><a class="btn" href="cart.php?remove=<?php echo $item['id']; ?>">Remove</a></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4"><strong>Total</strong></td>
            <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
        </tr>
    </table>
    <br>
    <a href="checkout.php" class="btn">Proceed to Checkout</a>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

