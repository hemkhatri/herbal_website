<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['worker_id'])) {
    header("Location: staff_login.php");
    exit;
}

$worker_id = $_SESSION['worker_id'];
$msg = "";

// --- 1. HANDLE ACTIONS ---

// Toggle Online/Offline Status
if (isset($_POST['toggle_status'])) {
    $new_status = ($_SESSION['worker_status'] == 'active') ? 'offline' : 'active';
    $stmt = $pdo->prepare("UPDATE delivery_personnel SET status = ? WHERE id = ?");
    if ($stmt->execute([$new_status, $worker_id])) {
        $_SESSION['worker_status'] = $new_status;
    }
}

// Claim an Order
if (isset($_POST['claim_order'])) {
    $order_id = $_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET status = 'assigned', delivery_person_id = ? WHERE id = ? AND status = 'pending'");
    if ($stmt->execute([$worker_id, $order_id])) {
        $msg = "Order #$order_id successfully assigned to you.";
    }
}

// Complete a Delivery
if (isset($_POST['complete_delivery'])) {
    $order_id = $_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET status = 'delivered' WHERE id = ? AND delivery_person_id = ?");
    if ($stmt->execute([$order_id, $worker_id])) {
        $msg = "Order #$order_id marked as DELIVERED.";
    }
}

// --- 2. FETCH DATA ---

// Active Assignments (Assigned to THIS worker)
$myOrdersStmt = $pdo->prepare("SELECT * FROM orders WHERE delivery_person_id = ? AND status = 'assigned' ORDER BY created_at DESC");
$myOrdersStmt->execute([$worker_id]);
$my_orders = $myOrdersStmt->fetchAll(PDO::FETCH_ASSOC);

// Pending Queue (Available for anyone to claim)
$pendingOrdersStmt = $pdo->prepare("SELECT * FROM orders WHERE status = 'pending' ORDER BY created_at DESC");
$pendingOrdersStmt->execute();
$pending_queue = $pendingOrdersStmt->fetchAll(PDO::FETCH_ASSOC);

$status_color = ($_SESSION['worker_status'] == 'active') ? 'bg-emerald-500' : 'bg-slate-500';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard | Luxury Aushadhi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        @import url('https://googleapis.com');
        .luxury-font { font-family: 'Playfair Display', serif; }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #f8fafc; }
    </style>
</head>
<body class="pb-32">

    <!-- Header -->
    <header class="p-6 flex justify-between items-center border-b border-white/5 bg-slate-950/50 backdrop-blur-xl sticky top-0 z-50">
        <div>
            <p class="text-[9px] uppercase tracking-[0.3em] text-slate-500 font-black mb-1">Authenticated Personnel</p>
            <h1 class="luxury-font text-xl text-emerald-400"><?= htmlspecialchars($_SESSION['worker_name']) ?></h1>
        </div>
        <form method="POST">
            <button name="toggle_status" class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 text-[9px] font-black uppercase tracking-widest transition-all bg-white/5 hover:bg-white/10">
                <span class="w-2 h-2 rounded-full <?= $status_color ?> shadow-[0_0_12px_rgba(16,185,129,0.4)]"></span>
                <?= strtoupper($_SESSION['worker_status']) ?>
            </button>
        </form>
    </header>

    <main class="p-6 max-w-2xl mx-auto">
        <?php if($msg): ?>
            <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] uppercase tracking-widest font-black rounded-2xl text-center">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <!-- Active Assignments -->
        <section class="mb-12">
            <h3 class="luxury-font text-2xl mb-6 flex items-center gap-3">
                My Tasks <span class="text-xs bg-emerald-500 text-white px-2 py-0.5 rounded-full"><?= count($my_orders) ?></span>
            </h3>
            
            <div class="space-y-6">
                <?php if(empty($my_orders)): ?>
                    <div class="p-12 text-center border-2 border-dashed border-white/5 rounded-[2.5rem]">
                        <p class="text-slate-600 text-[10px] uppercase tracking-widest">No active tasks assigned</p>
                    </div>
                <?php else: ?>
                    <?php foreach($my_orders as $order): ?>
                        <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-white/5 p-8 rounded-[2.5rem] shadow-2xl">
                            <div class="flex justify-between items-start mb-6">
                                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-tighter">Order #<?= $order['id'] ?></span>
                                <p class="text-[10px] text-slate-500 uppercase"><?= date('h:i A', strtotime($order['created_at'])) ?></p>
                            </div>
                            <h4 class="text-xl font-bold mb-4"><?= htmlspecialchars($order['customer_name']) ?></h4>
                            <div class="flex items-start gap-3 text-slate-400 mb-8">
                                <i class="fa-solid fa-location-dot mt-1 text-emerald-500"></i>
                                <p class="text-sm"><?= htmlspecialchars($order['shipping_address']) ?></p>
                            </div>
                            <div class="flex gap-3">
                                <a href="tel:<?= $order['customer_phone'] ?>" class="flex-1 bg-white/5 py-4 rounded-2xl text-[9px] font-black uppercase tracking-widest text-center border border-white/5 hover:bg-white/10 transition-all">Call</a>
                                <form method="POST" class="flex-1">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button name="complete_delivery" class="w-full bg-emerald-600 py-4 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-emerald-900/40">Complete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Global Queue -->
        <section>
            <h3 class="luxury-font text-2xl mb-6 text-slate-400">Available Queue</h3>
            <div class="space-y-4">
                <?php if(empty($pending_queue)): ?>
                    <p class="text-center py-10 text-slate-700 text-xs italic">Apothecary queue is currently clear...</p>
                <?php else: ?>
                    <?php foreach($pending_queue as $order): ?>
                        <div class="bg-white/5 border border-white/5 p-6 rounded-[2rem] flex items-center justify-between">
                            <div class="min-w-0">
                                <p class="text-lg font-bold truncate pr-4"><?= htmlspecialchars($order['customer_name']) ?></p>
                                <p class="text-[10px] text-slate-500 uppercase truncate"><?= htmlspecialchars($order['shipping_address']) ?></p>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button name="claim_order" class="bg-white text-black px-6 py-3 rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-emerald-400 transition-all">Claim</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-6 left-6 right-6 h-20 bg-slate-900/90 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] flex justify-around items-center z-50 shadow-2xl">
        <a href="#" class="text-emerald-500 flex flex-col items-center gap-1">
            <i class="fa-solid fa-house-chimney text-lg"></i>
            <span class="text-[7px] uppercase font-black tracking-widest">Home</span>
        </a>
        <a href="logout.php" class="text-slate-500 flex flex-col items-center gap-1">
            <i class="fa-solid fa-power-off text-lg"></i>
            <span class="text-[7px] uppercase font-black tracking-widest">Exit</span>
        </a>
    </nav>

</body>
</html>
