<?php 
session_start(); 
require '../../includes/db.php'; 
require '../../phpmailer/Exception.php'; 
require '../../phpmailer/PHPMailer.php'; 
require '../../phpmailer/SMTP.php'; 

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 

// 1. .ENV LOADER
function loadEnv($path) { 
    if (!file_exists($path)) return; 
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
    foreach ($lines as $line) { 
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue; 
        list($name, $value) = explode('=', $line, 2); 
        $_ENV[trim($name)] = trim($value); 
    } 
} 
loadEnv(__DIR__ . '/../../.env'); 

if (empty($_SESSION['cart']) && !isset($_SESSION['order_success'])) { 
    header("Location: index.php"); 
    exit(); 
} 

if (!empty($_SESSION['cart'])) {
    // 2. Fetch User Details
    $userStmt = $pdo->prepare("SELECT email, full_name, phone, address FROM users WHERE id = ?");
    $userStmt->execute([$_SESSION['user_id']]);
    $user = $userStmt->fetch();

    // 3. Fetch Product Details for Order and Email
    $cart_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($cart_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $table_rows = "";
    $grand_total = 0;
    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $subtotal = $p['price'] * $qty;
        $grand_total += $subtotal;
        $table_rows .= "
            <tr>
                <td style='padding:10px; border-bottom:1px solid #eee;'>{$p['name']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:center;'>{$qty}</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:right;'>Rs. ".number_format($subtotal)."</td>
            </tr>";
    }

    // 4. NOTIFY STAFF: Insert into Orders Table
    $orderSql = "INSERT INTO orders (customer_id, customer_name, customer_phone, shipping_address, total_amount, items_json) 
                 VALUES (?, ?, ?, ?, ?, ?)";
    $pdo->prepare($orderSql)->execute([
        $_SESSION['user_id'],
        $user['full_name'],
        $user['phone'] ?? 'N/A', 
        $user['address'] ?? 'N/A',
        $grand_total,
        json_encode($_SESSION['cart'])
    ]);

    // 5. SEND EMAIL TO CUSTOMER
    if ($user) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP(); 
            $mail->Host = '://gmail.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = $_ENV['SMTP_EMAIL']; 
            $mail->Password = $_ENV['SMTP_PASSWORD']; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587; 
            $mail->SMTPOptions = array('ssl'=>['verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true]); 

            $mail->setFrom($_ENV['SMTP_EMAIL'], 'Luxury Aushadhi'); 
            $mail->addAddress($user['email']); 
            $mail->isHTML(true); 
            $mail->Subject = 'Order Confirmed - Luxury Aushadhi'; 
            $mail->Body = "
                <div style='font-family:serif; max-width:600px; margin:auto; padding:30px; background-color:#FDFCF9; border:1px solid #eee;'>
                    <h1 style='color:#2D4030; text-align:center;'>Order Confirmed</h1>
                    <p>Dear {$user['full_name']}, your order is being prepared.</p>
                    <table style='width:100%; border-collapse:collapse; margin:20px 0;'>
                        <tr style='background:#2D4030; color:white;'>
                            <th style='padding:10px; text-align:left;'>Item</th>
                            <th style='padding:10px;'>Qty</th>
                            <th style='padding:10px; text-align:right;'>Total</th>
                        </tr>
                        $table_rows
                    </table>
                    <p style='text-align:right; font-weight:bold; font-size:18px;'>Total: Rs. ".number_format($grand_total)."</p>
                    <p style='font-size:12px; color:#888; margin-top:20px;'>Shipping to: {$user['address']}</p>
                </div>";
            $mail->send(); 
        } catch (Exception $e) {}
    }

    // 6. CLEAR CART
    $pdo->prepare("UPDATE users SET cart_data = NULL WHERE id = ?")->execute([$_SESSION['user_id']]); 
    unset($_SESSION['cart']); 
    $_SESSION['order_success'] = true;
}
unset($_SESSION['order_success']); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Order Confirmed | Luxury Aushadhi</title> 
      <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style> 
        @import url('https://googleapis.com'); 
        .luxury-font { font-family: 'Playfair Display', serif; } 
    </style> 
    <script>
        window.history.replaceState(null, null, "index.php");
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () { window.location.href = "index.php"; };
    </script>
</head> 
<body class="bg-[#FDFCF9] min-h-screen flex items-center justify-center p-6 text-center"> 
    <div class="max-w-md w-full"> 
        <div class="w-20 h-20 bg-[#2D4030] rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl"> 
            <i class="fa-solid fa-check text-white text-3xl"></i> 
        </div> 
        <h1 class="luxury-font text-4xl mb-4">Order Placed</h1> 
        <p class="text-gray-400 text-sm leading-relaxed mb-10"> 
            Thank you for your purchase. A confirmation email has been sent to <br>
            <strong class="text-gray-800"><?= htmlspecialchars($user['email'] ?? 'your inbox') ?></strong>.
        </p> 
        <a href="index.php" class="inline-block bg-[#2D4030] text-white px-10 py-4 rounded-full text-[10px] uppercase tracking-[0.2em] font-bold hover:bg-black transition-all"> 
            Return to Collection 
        </a> 
    </div> 
</body> 
</html>
