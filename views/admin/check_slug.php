<?php
require '../../includes/db.php';
$slug = $_GET['slug'] ?? '';
if ($slug) {
    $stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
    $stmt->execute([$slug]);
    echo $stmt->fetch() ? "exists" : "ok";
}
