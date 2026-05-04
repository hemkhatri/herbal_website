<?php
session_start();
require '../../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if ($product_id) {
    // 1. Manage the Session Cart (Runtime)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$product_id] = 1;

    // 2. Manage Guest Cookie (Persistent for 30 days)
    // If not logged in, store the session cart into a cookie
    if (!isset($_SESSION['user_id'])) {
        $cookie_data = json_encode($_SESSION['cart']);
        setcookie('guest_cart', $cookie_data, time() + (86400 * 30), "/"); // 30 days
    } 
    // 3. If User is Logged In, sync to Database
    else {
        $user_id = $_SESSION['user_id'];
        
        // Check if item already exists in user's DB cart to avoid duplicates
        $stmt = $pdo->prepare("SELECT cart_data FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        $db_cart = $user['cart_data'] ? json_decode($user['cart_data'], true) : [];
        $db_cart[$product_id] = 1; // Add/Update item
        
        $updated_json = json_encode($db_cart);
        $stmt = $pdo->prepare("UPDATE users SET cart_data = ? WHERE id = ?");
        $stmt->execute([$updated_json, $user_id]);
        
        // Sync session with DB to be safe
        $_SESSION['cart'] = $db_cart;
    }

    echo json_encode([
        'status' => 'success',
        'total_count' => count($_SESSION['cart'])
    ]);
}
