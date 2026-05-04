<?php
session_start();

// 1. Database Connection
require '../../includes/db.php'; 

// 2. Security Check: If no registration is in progress, kick them out
if (!isset($_SESSION['temp_user'])) {
    header("Location: signup.php");
    exit();
}

$error = "";
$user_email = $_SESSION['temp_user']['email'];

// 3. Handle OTP Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST['otp']);
    
    // Check if OTP matches what's in the session
    if ($user_otp == $_SESSION['temp_user']['code']) {
        
        try {
            // Begin transaction to ensure data integrity
            $pdo->beginTransaction();

            // Insert the new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['temp_user']['fullname'],
                $_SESSION['temp_user']['email'],
                $_SESSION['temp_user']['password'],
                $_SESSION['temp_user']['phone']
            ]);

            $new_user_id = $pdo->lastInsertId();

            // Commit the transaction
            $pdo->commit();

            // 4. Log the user in automatically
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['user_name'] = $_SESSION['temp_user']['fullname'];
            
            // 5. Cleanup temporary registration data
            unset($_SESSION['temp_user']);

            // Redirect to home page with success status
            header("Location: ../shop/index.php?registration=success");


            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == 23000) { // Duplicate entry error code
                $error = "This email was registered by someone else just now.";
            } else {
                $error = "Database Error: " . $e->getMessage();
            }
        }
    } else {
        $error = "The 6-digit code you entered is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Identity | Luxury Aushadhi</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { background: #FDFCF9; font-family: 'Inter', sans-serif; }
        .luxury-font { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <h1 class="luxury-font text-3xl mb-2">Authenticating</h1>
        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-bold mb-10">
            Code sent to: <span class="text-gray-900"><?= htmlspecialchars($user_email) ?></span>
        </p>
        
        <div class="bg-white p-10 rounded-[32px] shadow-xl border border-stone-100">
            <form method="POST" class="space-y-6">
                <input type="text" name="otp" placeholder="000000" maxlength="6" required 
                    class="w-full text-center text-3xl tracking-[0.5em] py-5 bg-stone-50 border border-stone-100 rounded-2xl focus:outline-none focus:border-[#2D4030]">
                
                <?php if($error): ?>
                    <p class="text-red-500 text-[10px] font-bold uppercase"><?= $error ?></p>
                <?php endif; ?>

                <button type="submit" class="w-full bg-[#2D4030] text-white py-5 rounded-2xl font-bold uppercase tracking-widest text-[11px] hover:bg-black transition-all">
                    Verify Identity
                </button>
            </form>
            <p class="mt-6 text-[10px] text-gray-400 uppercase tracking-widest">
                Didn't get a code? <a href="signup.php" class="text-gray-900 font-bold underline">Try again</a>
            </p>
        </div>
    </div>
</body>
</html>
