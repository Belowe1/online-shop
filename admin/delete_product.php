<?php
require_once '../config/db.php'; session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    csrf_verify();
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: dashboard.php');
exit;
