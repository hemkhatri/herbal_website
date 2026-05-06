<?php 
session_start(); 
require '../../includes/db.php'; 

if (!isset($_SESSION['user_id'])) { 
    header("Location: ../auth/signup.php"); 
    exit(); 
} 

$update_msg = ""; 

// --- HANDLE PROFILE UPDATE --- 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) { 
    $f_name = trim($_POST['first_name']);
    $l_name = trim($_POST['last_name']);
    $new_address = trim($_POST['address']); 
    $new_phone = trim($_POST['phone_number']);

    if (!empty($f_name) && !empty($l_name)) { 
        $updateStmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, address = ?, phone_number = ? WHERE id = ?"); 
        if ($updateStmt->execute([$f_name, $l_name, $new_address, $new_phone, $_SESSION['user_id']])) { 
            $_SESSION['user_name'] = $f_name . " " . $l_name; 
            $update_msg = "Profile updated successfully."; 
        } 
    } 
} 

// Fetch fresh user data using your specific column names
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?"); 
$stmt->execute([$_SESSION['user_id']]); 
$user = $stmt->fetch(); 

// Logic for initials using your database fields
$initials = strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); 
$full_display_name = htmlspecialchars($user['first_name'] . " " . $user['last_name']);
?> 

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>My Profile | Luxury Aushadhi</title> 
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="stylesheet" href="https://cloudflare.com">
    <link href="https://googleapis.com" rel="stylesheet">
    <style> 
        body { background: #FDFCF9; font-family: 'Inter', sans-serif; } 
        .luxury-font { font-family: 'Playfair Display', serif; } 
    </style> 
</head> 
<body class="p-6 md:p-20"> 
    <div class="max-w-4xl mx-auto"> 
        <a href="../shop/index.php" class="inline-flex items-center text-[10px] uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors mb-10"> 
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Collection 
        </a> 

        <?php if($update_msg): ?> 
            <div class="mb-6 p-4 bg-green-50 text-green-700 text-[10px] uppercase tracking-widest font-bold rounded-2xl border border-green-100 animate-pulse"> 
                <i class="fa-solid fa-check-circle mr-2"></i> <?= $update_msg ?> 
            </div> 
        <?php endif; ?> 

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12"> 
            <div class="lg:col-span-1 text-center"> 
                <div class="bg-white p-8 rounded-[32px] border border-stone-100 shadow-xl shadow-stone-200/50"> 
                    <div class="w-24 h-24 bg-[#2D4030] text-white flex items-center justify-center rounded-full mx-auto mb-6 text-2xl font-bold luxury-font border-4 border-stone-50"> 
                        <?= $initials ?> 
                    </div> 
                    <h2 class="luxury-font text-2xl text-gray-900"><?= $full_display_name ?></h2> 
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mt-1 font-bold">Member Account</p> 
                    
                    <div class="mt-8 pt-8 border-t border-stone-50 flex flex-col gap-3 text-xs text-gray-500"> 
                        <p>Joined: <?= date('M Y', strtotime($user['created_at'])) ?></p> 
                        <a href="../auth/logout.php" class="text-red-400 hover:text-red-600 transition-colors mt-4">Sign Out</a> 
                    </div> 
                </div> 
            </div> 

            <div class="lg:col-span-2"> 
                <h1 class="luxury-font text-4xl mb-2">Account Settings</h1> 
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-bold mb-10">Update your public identity</p> 
                
                <form method="POST" class="space-y-8"> 
                    <div class="space-y-6"> 
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div> 
                                <label class="block text-[10px] uppercase tracking-[0.15em] font-black text-gray-400 mb-2 ml-1">First Name</label> 
                                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" class="w-full p-4 bg-white border border-stone-100 rounded-2xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2D4030]/5 focus:border-[#2D4030] transition-all" required> 
                            </div> 
                            <div> 
                                <label class="block text-[10px] uppercase tracking-[0.15em] font-black text-gray-400 mb-2 ml-1">Last Name</label> 
                                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" class="w-full p-4 bg-white border border-stone-100 rounded-2xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2D4030]/5 focus:border-[#2D4030] transition-all" required> 
                            </div> 
                        </div>

                        <div> 
                            <label class="block text-[10px] uppercase tracking-[0.15em] font-black text-gray-400 mb-2 ml-1">Default Delivery Address</label> 
                            <textarea name="address" rows="2" class="w-full p-4 bg-white border border-stone-100 rounded-2xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2D4030]/5 focus:border-[#2D4030] transition-all" placeholder="Street, City, Nepal"><?= htmlspecialchars($user['address'] ?? '') ?></textarea> 
                        </div> 

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 
                            <div> 
                                <label class="block text-[10px] uppercase tracking-[0.15em] font-black text-gray-400 mb-2 ml-1">Email (Private)</label> 
                                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly class="w-full p-4 bg-stone-50 border border-stone-100 rounded-2xl text-sm text-gray-400 cursor-not-allowed"> 
                            </div> 
                            <div> 
                                <label class="block text-[10px] uppercase tracking-[0.15em] font-black text-gray-400 mb-2 ml-1">Verified Phone</label> 
                                <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>" class="w-full p-4 bg-white border border-stone-100 rounded-2xl text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2D4030]/5 focus:border-[#2D4030] transition-all"> 
                            </div> 
                        </div> 
                    </div> 

                    <button type="submit" name="update_profile" class="bg-[#2D4030] text-white px-10 py-4 rounded-2xl text-[10px] uppercase tracking-[0.2em] font-bold hover:bg-black transition-all shadow-lg active:scale-95"> 
                        Save Changes 
                    </button> 
                </form> 

                <div class="mt-12 p-6 bg-stone-50 rounded-2xl border border-stone-100"> 
                    <h4 class="text-[10px] uppercase tracking-widest font-black text-gray-900 mb-2">Privacy Note</h4> 
                    <p class="text-[10px] text-gray-400 leading-relaxed uppercase tracking-tighter"> 
                        Email verification is permanent. If you must change your login credentials, please contact our concierge service. 
                    </p> 
                </div> 
            </div> 
        </div> 
    </div> 
</body> 
</html>
