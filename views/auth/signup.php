<?php
session_start();
require '../../includes/db.php';
require '../../phpmailer/Exception.php';
require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function loadEnv($path)
{
    if (!file_exists($path))
        return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false)
            continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}
loadEnv(__DIR__ . '/../../.env');

if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['auth_email']);
    header("Location: /aushadhi-platform/index.php");
    exit;
}

$session_email = $_SESSION['auth_email'] ?? '';
if (empty($session_email) && !isset($_GET['action'])) {
    header("Location: /aushadhi-platform/index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Split Full Name into First and Last Name for your new table structure
    $parts = explode(" ", trim($_POST['fullname']), 2);
    $firstName = $_POST['first_name'] ?? ''; 
    $lastName = $_POST['last_name'] ?? '';

    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone_full'];
    $verification_code = rand(100000, 999999);

    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        $error = "This email is already registered. Please login instead.";
    } else {
        // STORE IN SESSION (Updated with first/last name)
        $_SESSION['temp_user'] = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
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

            $mail->setFrom($_ENV['SMTP_EMAIL'], 'Luxury Aushadhi');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Account - Aushadi Herbal Essence';
            $mail->Body = "
                <div style='font-family: sans-serif; max-width: 500px; margin: auto; color: #333; line-height: 1.6; border: 1px solid #f0f0f0; padding: 30px; border-radius: 12px; background-color: #ffffff;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h2 style='color: #2D4030; margin: 0; font-size: 24px;'>Aushadhi Herbal Essence</h2>
                        <p style='font-size: 12px; color: #8ba888; text-transform: uppercase; letter-spacing: 2px; margin-top: 5px;'>Natural & Authentic</p>
                    </div>

                    <p>Dear Customer,</p>
                    <p>Welcome to <b>Aushadhi Herbal Essence</b> 🌿</p>
                    <p>To complete your registration and secure your account, please verify your email address using the verification code below:</p>
                    
                    <div style='background: #F9FBF9; border: 1px dashed #2D4030; padding: 20px; text-align: center; border-radius: 8px; margin: 25px 0;'>
                        <p style='margin: 0; font-size: 14px; color: #666;'>🔐 Your Verification Code</p>
                        <h1 style='margin: 10px 0; font-size: 36px; color: #2D4030; letter-spacing: 5px;'>$verification_code</h1>
                        <p style='margin: 0; font-size: 12px; color: #999;'>Valid for 10 minutes</p>
                    </div>

                    <p style='font-size: 14px;'>Please do not share this code with anyone for security reasons. If you did not request this verification, you can safely ignore this email.</p>

                    <p style='font-size: 14px;'>Need help? Contact our support team at <a href='mailto: products@gmail.com' style='color: #2D4030; text-decoration: none; font-weight: bold;'>aushadiproducts@gmail.com</a></p>aushadi
                    
                    <p style='margin-top: 30px;'>We’re excited to have you with us!<br>
                    Warm regards,<br>
                    <b>Aushadhi Herbal Essence Team</b></p>
                    
                    <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                    <p style='font-size: 11px; color: #aaa; text-align: center; font-style: italic;'>
                        This is an automated message. Please do not reply to this email.
                    </p>
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
        :root {
            --brand-green: #2D4030;
        }

        body {
            background: #FDFCF9;
            font-family: 'Inter', sans-serif;
        }

        .luxury-font {
            font-family: 'Playfair Display', serif;
        }

        .input-field {
            width: 100%;
            padding: 1rem 1.5rem;
            background-color: #f9f8f6;
            border: 1px solid #f1f0ee;
            border-radius: 1rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--brand-green);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(45, 64, 48, 0.05);
        }

        .label-style {
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-size: 10px;
            font-weight: 900;
            color: #9ca3af;
            margin-bottom: 0.5rem;
            margin-left: 0.25rem;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="luxury-font text-4xl text-gray-900 mb-2">Luxury Aushadhi</h1>
            <?php if ($session_email): ?>
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-bold">Joining with
                    <b><?= htmlspecialchars($session_email) ?></b>
                </p>
            <?php else: ?>
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-bold">Create Your Account</p>
            <?php endif; ?>
        </div>

        <div class="bg-white p-10 rounded-[32px] shadow-xl border border-stone-100">
            <?php if ($error): ?>
                <div
                    class="mb-5 p-3 bg-red-50 text-red-600 text-xs rounded-xl border border-red-100 text-center font-bold italic">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form id="registerForm" action="" method="POST" class="space-y-5">
                <!-- Split Name Row -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-style">First Name</label>
                        <input type="text" name="first_name" required class="input-field" placeholder="Aarav" autofocus>
                    </div>
                    <div>
                        <label class="label-style">Last Name</label>
                        <input type="text" name="last_name" required class="input-field" placeholder="Sharma">
                    </div>
                </div>

                <!-- Rest of your fields (Email, Address, etc.) -->


                <div>
                    <label class="label-style">Email Address</label>
                    <!-- Pre-filled but editable -->
                    <input type="email" name="email" value="<?= htmlspecialchars($session_email) ?>" required
                        class="input-field">
                </div>

                <div>
                    <label class="label-style">Address</label>
                    <input type="text" name="address" required class="input-field"
                        placeholder="Street, City (e.g. Baluwatar, Kathmandu)">
                </div>

                <div>
                    <label class="label-style">Phone Number (Nepal)</label>
                    <input type="tel" id="phone" required class="input-field" placeholder="98XXXXXXXX"
                        pattern="9[0-9]{9}">
                    <input type="hidden" id="formatted_phone" name="phone_full">
                </div>

                <div>
                    <label class="label-style">Password</label>
                    <div class="relative flex items-center">
                        <input type="password" id="password" name="password" required class="input-field pr-12"
                            placeholder="••••••••">
                        <div class="absolute right-5 cursor-pointer"
                            onclick="toggleVisibility('password', this.querySelector('i'))">
                            <i class="fa-solid fa-eye text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label-style">Confirm Password</label>
                    <div class="relative flex items-center">
                        <input type="password" id="confirm_password" required class="input-field pr-12"
                            placeholder="••••••••">
                        <div class="absolute right-5 cursor-pointer"
                            onclick="toggleVisibility('confirm_password', this.querySelector('i'))">
                            <i class="fa-solid fa-eye text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div id="error-msg"
                    class="hidden bg-red-50 text-red-600 text-[10px] uppercase tracking-wider p-3 rounded-xl border border-red-100 font-bold text-center">
                    Passwords do not match
                </div>

                <button type="submit"
                    class="w-full bg-[#2D4030] text-white py-5 rounded-2xl font-bold uppercase tracking-widest text-[11px] hover:bg-black transition-all">
                    Join the Collection
                </button>
            </form>

            <div class="text-center mt-8 space-y-3">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">
                    Already a member?
                    <a href="login.php" class="text-[#2D4030] hover:underline ml-1">Login here</a>
                </p>

                <!-- Small utility to clear session if they really want to change email -->
                <a href="signup.php?action=clear"
                    class="block text-[9px] uppercase tracking-[0.2em] text-gray-300 hover:text-red-800 transition-colors">
                    Reset Email Choice
                </a>
            </div>

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