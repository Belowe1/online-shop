<?php
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('Product not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($quantity < 1) $quantity = 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }

    header('Location: cart.php');
    exit;
}
?>

<div class="card" style="max-width:800px; margin:auto;">
    <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/600x400?text=No+Image'; ?>" alt="product">
    <div class="card-body">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
        <p class="price">$<?php echo $product['price']; ?></p>
        <p><strong>Stock:</strong> <?php echo $product['stock']; ?></p>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <form method="post" style="margin-top:20px;">
            <label>Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" max="99" style="width:120px; padding:8px;">
            <button type="submit" class="btn">Add to Cart</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

