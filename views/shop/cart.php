<?php
session_start();
require '../../includes/db.php';

// Redirect if not logged in (to get address/phone)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// --- FIX 1: Fetch 'address' in the query ---
$userStmt = $pdo->prepare("SELECT phone, full_name, address FROM users WHERE id = ?");
$userStmt->execute([$_SESSION['user_id']]);
$userData = $userStmt->fetch();

// --- LOGIC HANDLER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $p_id = $_POST['product_id'];
        if (isset($_POST['remove_item'])) {
            unset($_SESSION['cart'][$p_id]);
        } elseif (isset($_POST['action'])) {
            $current_qty = $_SESSION['cart'][$p_id] ?? 1;
            $_SESSION['cart'][$p_id] = ($_POST['action'] === 'increase') ? $current_qty + 1 : max(1, $current_qty - 1);
        }
        // Sync DB
        $json_cart = json_encode($_SESSION['cart']);
        $pdo->prepare("UPDATE users SET cart_data = ? WHERE id = ?")->execute([$json_cart, $_SESSION['user_id']]);
    }
    header("Location: cart.php");
    exit();
}

$cart_items = [];
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT p.id, p.name, p.price, pi.image_url FROM products p LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.id IN ($placeholders) AND (pi.image_type = 'main' OR pi.image_type IS NULL) GROUP BY p.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apothecary Cart | Luxury Aushadhi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fixed FontAwesome and Google Fonts links -->
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        .luxury-font {
            font-family: 'Playfair Display', serif;
        }

        body {
            background: #FDFCF9;
            font-family: 'Inter', sans-serif;
            color: #1c1c1c;
        }
    </style>
</head>

<body class="p-6 md:p-20">
    <div class="max-w-5xl mx-auto">
        <header class="mb-16 border-b border-stone-100 pb-10 flex justify-between items-end">
            <h1 class="luxury-font text-5xl">Your Cart</h1>
            <a href="index.php"
                class="text-[10px] uppercase tracking-widest text-gray-400 border-b border-stone-200">Back to Shop</a>
        </header>

        <?php if (empty($cart_items)): ?>
            <p class="text-center py-20 text-gray-400 italic">Your apothecary is empty.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                <div class="lg:col-span-2 space-y-8">
                    <?php foreach ($cart_items as $item):
                        $qty = $_SESSION['cart'][$item['id']];
                        $subtotal = $item['price'] * $qty;
                        $total_price += $subtotal;
                        ?>
                        <div
                            class="flex items-center gap-8 bg-white p-6 rounded-[32px] border border-stone-50 shadow-sm relative">
                            <form method="POST" class="absolute -top-3 -right-3 z-30">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                <button type="submit" name="remove_item"
                                    class="w-8 h-8 bg-white border border-stone-100 shadow-md rounded-full text-stone-300 hover:text-red-500 flex items-center justify-center">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </form>
                            <img src="<?= htmlspecialchars($item['image_url'] ?? 'placeholder.jpg') ?>"
                                class="w-32 h-32 rounded-2xl object-cover">
                            <div class="flex-1">
                                <h3 class="luxury-font text-xl"><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="text-xs text-gray-400 mb-4">Rs. <?= number_format($item['price']) ?></p>
                                <form method="POST" class="flex items-center gap-4">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <div class="flex items-center border border-stone-100 rounded-full px-2 py-1">
                                        <!-- Submit button for decrease -->
                                        <button type="submit" name="action" value="decrease"
                                            class="px-2 hover:text-red-500">-</button>
                                        <span class="w-8 text-center text-xs font-bold"><?= $qty ?></span>
                                        <!-- Submit button for increase -->
                                        <button type="submit" name="action" value="increase"
                                            class="px-2 hover:text-green-600">+</button>
                                    </div>
                                    <div class="text-sm font-bold text-[#2D4030]">Rs. <?= number_format($subtotal) ?></div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[40px] border border-stone-100 shadow-xl sticky top-10">
                        <h4 class="luxury-font text-2xl mb-6">Summary</h4>
                        <div class="space-y-3 mb-6 text-xs text-gray-500">
                            <div class="flex justify-between text-gray-400 uppercase tracking-widest text-[10px]">
                                <span>Subtotal</span><span>Rs. <?= number_format($total_price) ?></span></div>
                            <div class="flex justify-between text-gray-400 uppercase tracking-widest text-[10px]">
                                <span>Shipping</span><span class="text-green-600 font-bold">Free</span></div>

                            <div class="pt-6 mt-4 border-t border-stone-100">
                                <label
                                    class="font-bold uppercase tracking-widest text-[9px] text-stone-400 block mb-1">Shipping
                                    Address</label>
                                <!-- FIX: Now pulls address from userData -->
                                <p class="text-gray-800 italic leading-relaxed">
                                    <?= !empty($userData['address']) ? htmlspecialchars($userData['address']) : '<span class="text-red-300">My Profile -> Edit -> Add the Address</span>' ?>
                                </p>

                                <label
                                    class="font-bold uppercase tracking-widest text-[9px] text-stone-400 block mt-4 mb-1">Contact
                                    Phone</label>
                                <p class="text-gray-800"><?= htmlspecialchars($userData['phone'] ?? 'Not set') ?></p>
                            </div>
                        </div>

                        <div class="border-t border-stone-50 pt-6 mb-8 flex justify-between items-center">
                            <span class="uppercase tracking-widest text-[10px] font-black text-stone-400">Total</span>
                            <span class="luxury-font text-2xl">Rs. <?= number_format($total_price) ?></span>
                        </div>

                        <button onclick="confirmOrder()"
                            class="w-full bg-[#2D4030] text-white py-5 rounded-2xl font-bold uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-lg active:scale-95">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div id="orderModal"
        class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-stone-900/40 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div
            class="bg-white p-10 rounded-[40px] shadow-2xl relative z-10 max-w-sm w-full transform scale-90 transition-all duration-300 text-center border border-stone-100">
            <div
                class="w-16 h-16 bg-[#2D4030]/10 text-[#2D4030] rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-leaf text-2xl"></i>
            </div>
            <h3 class="luxury-font text-3xl mb-2">Finalize Order?</h3>
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-8">Total: Rs.
                <?= number_format($total_price) ?></p>

            <div class="flex flex-col gap-3">
                <button onclick="executeOrder()"
                    class="w-full bg-[#2D4030] text-white py-4 rounded-2xl font-bold uppercase tracking-widest text-[10px] hover:bg-black transition-all shadow-md">
                    Confirm Purchase
                </button>
                <button onclick="closeModal()"
                    class="w-full py-4 text-[10px] uppercase tracking-widest font-bold text-gray-400 hover:text-stone-800 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <script>
        const modal = document.getElementById('orderModal');
        const modalContent = modal.querySelector('div:last-child');

        function confirmOrder() {
            // Show Modal
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-90');
            modalContent.classList.add('scale-100');
        }

        function closeModal() {
            // Hide Modal
            modal.classList.add('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-90');
        }

        function executeOrder() {
            // Luxury feedback before redirect
            const btn = event.target;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin"></i> Processing...';
            btn.style.pointerEvents = 'none';

            window.location.href = "checkout.php";
        }

        // Close modal if user clicks the backdrop
        modal.querySelector('.absolute').addEventListener('click', closeModal);
    </script>
</body>

</html>