<!-- admin| admin123 -->
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 2) . '/includes/db.php'; 

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin-dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$user]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($pass, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin-dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Aushadhi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link href="https://cloudflare.com" rel="stylesheet"> -->
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <!-- Logo/Brand Area -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-xl mb-4 shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-lock text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Aushadhi Admin</h1>
            <p class="text-slate-400 mt-2">Secure access to your dashboard</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-8 rounded-2xl shadow-2xl">
            <?php if(isset($error)): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 text-red-400 text-sm rounded-lg flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="username" required
                            class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Enter admin username">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-lg shadow-indigo-600/30 transition duration-200 transform active:scale-[0.98]">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-8">
            &copy; <?= date('Y') ?> Aushadhi Platform. All rights reserved.
        </p>
    </div>

</body>
</html>
