<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = '127.0.0.1';
$port = '3306';
$dbname = 'clothing_shop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
