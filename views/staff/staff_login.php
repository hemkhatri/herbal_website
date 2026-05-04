<?php
session_start();
require_once dirname(__DIR__, 2) . '/includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['worker_id'])) {
    header("Location: staff_dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    // Workers login via Phone Number (more common for delivery staff)
    $stmt = $pdo->prepare("SELECT * FROM delivery_personnel WHERE phone = ?");
    $stmt->execute([$phone]);
    $worker = $stmt->fetch();

    if ($worker && password_verify($pass, $worker['password'])) {
        // Set worker-specific sessions
        $_SESSION['worker_id'] = $worker['id'];
        $_SESSION['worker_name'] = $worker['full_name'];
        $_SESSION['worker_status'] = $worker['status'];

        header("Location: staff_dashboard.php");
        exit;
    } else {
        $error = "Invalid phone number or password.";
    }
echo password_hash("hello", PASSWORD_DEFAULT);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Login | Luxury Aushadhi</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        @import url('https://googleapis.com');
        .luxury-font { font-family: 'Playfair Display', serif; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-600 rounded-3xl mb-6 shadow-2xl shadow-emerald-500/20">
                <i class="fa-solid fa-truck-fast text-3xl text-white"></i>
            </div>
            <h1 class="luxury-font text-4xl text-white tracking-wide">Staff Access</h1>
            <p class="text-slate-400 mt-2 uppercase tracking-[0.2em] text-[10px] font-bold">Delivery & Logistics</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-10 rounded-[2.5rem] shadow-2xl">
            <?php if(isset($error)): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-2xl flex items-center font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-circle-exclamation mr-3"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-black text-slate-500 mb-2 ml-1">Phone Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </span>
                        <input type="tel" name="phone" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-800/50 border border-slate-700 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-600"
                            placeholder="98XXXXXXXX">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] font-black text-slate-500 mb-2 ml-1">Staff Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </span>
                        <input type="password" name="password" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-800/50 border border-slate-700 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-5 rounded-2xl shadow-lg shadow-emerald-900/20 transition-all transform active:scale-[0.98] uppercase tracking-[0.2em] text-[11px]">
                    Sign In to Portal
                </button>
            </form>
        </div>

        <p class="text-center text-slate-600 text-[10px] mt-10 uppercase tracking-widest">
            &copy; <?= date('Y') ?> Luxury Aushadhi Logistics Section
        </p>
    </div>

</body>
</html>
