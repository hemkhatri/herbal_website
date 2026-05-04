<?php
session_start();
// 1. DATABASE CONNECTION (Ensure this path is correct)
require '../../includes/db.php';

// 2. PHPMAILER FILES
require '../../phpmailer/Exception.php';
require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 3. .ENV LOADER
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

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullname'];
    $email = $_POST['email'];
    $address = $_POST['address']; // New Address Field
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone_full'];
    $verification_code = rand(100000, 999999);

    // 4. CHECK IF EMAIL EXISTS
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);

    if ($checkStmt->fetch()) {
        $error = "This email is already registered. Please login instead.";
    } else {
        // 5. STORE IN SESSION (Added Address)
        $_SESSION['temp_user'] = [
            'fullname' => $fullName,
            'email' => $email,
            'address' => $address,
            'password' => $password,
            'phone' => $phone,
            'code' => $verification_code
        ];

        // 6. SEND EMAIL
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_EMAIL'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]
            );

            $mail->setFrom($_ENV['SMTP_EMAIL'], 'Luxury Aushadhi');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your Verification Code';
            $mail->Body = "
                <div style='font-family:serif; text-align:center; padding:20px;'>
                    <h1 style='color:#2D4030;'>Luxury Aushadhi</h1>
                    <p style='text-transform:uppercase; letter-spacing:1px;'>Your Verification Code</p>
                    <h2 style='font-size:40px; border:1px solid #eee; display:inline-block; padding:10px 30px;'>$verification_code</h2>
                </div>";

            $mail->send();
            header("Location: verify.php");
            exit();
        } catch (Exception $e) {
            $error = "Mail error: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Luxury Aushadhi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        :root { --brand-green: #2D4030; }
        body { background: #FDFCF9; font-family: 'Inter', sans-serif; }
        .luxury-font { font-family: 'Playfair Display', serif; }
        .input-field { width: 100%; padding: 1rem 1.5rem; background-color: #f9f8f6; border: 1px solid #f1f0ee; border-radius: 1rem; font-size: 0.875rem; transition: all 0.2s; }
        .input-field:focus { outline: none; border-color: var(--brand-green); background-color: white; box-shadow: 0 0 0 4px rgba(45, 64, 48, 0.05); }
        .label-style { display: block; text-transform: uppercase; letter-spacing: 0.15em; font-size: 10px; font-weight: 900; color: #9ca3af; margin-bottom: 0.5rem; margin-left: 0.25rem; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="luxury-font text-4xl text-gray-900 mb-2">Luxury Aushadhi</h1>
            <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-bold">Create Your Account</p>
        </div>

        <div class="bg-white p-10 rounded-[32px] shadow-xl border border-stone-100">
            <?php if($error): ?>
                <div class="mb-5 p-3 bg-red-50 text-red-600 text-xs rounded-xl border border-red-100 text-center font-bold italic"><?= $error ?></div>
            <?php endif; ?>

            <form id="registerForm" action="" method="POST" class="space-y-5">
                <div>
                    <label class="label-style">Full Name</label>
                    <input type="text" name="fullname" required class="input-field" placeholder="Aarav Sharma">
                </div>
                <div>
                    <label class="label-style">Email Address</label>
                    <input type="email" name="email" required class="input-field" placeholder="nature@himalaya.com">
                </div>
                <!-- NEW ADDRESS FIELD -->
                <div>
                    <label class="label-style">Address</label>
                    <input type="text" name="address" required class="input-field" placeholder="Street, City (e.g. Baluwatar, Kathmandu)">
                </div>
                <div>
                    <label class="label-style">Phone Number (Nepal)</label>
                    <input type="tel" id="phone" required class="input-field" placeholder="98XXXXXXXX" pattern="9[0-9]{9}">
                    <input type="hidden" id="formatted_phone" name="phone_full">
                </div>
                <div>
                    <label class="label-style">Password</label>
                    <div class="relative flex items-center">
                        <input type="password" id="password" name="password" required class="input-field pr-12" placeholder="••••••••">
                        <div class="absolute right-5 cursor-pointer" onclick="toggleVisibility('password', this.querySelector('i'))">
                            <i class="fa-solid fa-eye text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="label-style">Confirm Password</label>
                    <div class="relative flex items-center">
                        <input type="password" id="confirm_password" required class="input-field pr-12" placeholder="••••••••">
                        <div class="absolute right-5 cursor-pointer" onclick="toggleVisibility('confirm_password', this.querySelector('i'))">
                            <i class="fa-solid fa-eye text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div id="error-msg" class="hidden bg-red-50 text-red-600 text-[10px] uppercase tracking-wider p-3 rounded-xl border border-red-100 font-bold text-center"> Passwords do not match </div>
                
                <button type="submit" class="w-full bg-[#2D4030] text-white py-5 rounded-2xl font-bold uppercase tracking-widest text-[11px] hover:bg-black transition-all"> Join the Collection </button>
            </form>
        </div>
    </div>

    <script>
        function toggleVisibility(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            const pass = document.getElementById('password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            const phoneInput = document.getElementById('phone');
            const formattedPhoneHidden = document.getElementById('formatted_phone');
            if (pass !== confirmPass) {
                e.preventDefault();
                document.getElementById('error-msg').classList.remove('hidden');
                return;
            }
            formattedPhoneHidden.value = "+977" + phoneInput.value;
        });
    </script>
</body>
</html>
