<?php
session_start();
require_once dirname(__DIR__, 2) . '/includes/db.php';

// 1. Handle "Not you?" - Clear session but STAY on this page
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['auth_email']);
    // No header redirect here! This prevents the "Too many redirects" loop.
}

// 2. Security Check: Only redirect home if there's no email AND they didn't just click clear
if (empty($_SESSION['auth_email']) && !isset($_GET['action'])) {
    header("Location: /aushadhi-platform/index.php");
    exit;
}

$session_email = $_SESSION['auth_email'] ?? '';

// 3. Process Login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        // --- CART MERGE LOGIC ---
        $db_cart = !empty($user['cart_data']) ? json_decode($user['cart_data'], true) : [];
        if (isset($_COOKIE['guest_cart'])) {
            $guest_cart = json_decode($_COOKIE['guest_cart'], true);
            $merged_cart = array_replace($db_cart, $guest_cart);

            $stmt = $pdo->prepare("UPDATE users SET cart_data = ? WHERE id = ?");
            $stmt->execute([json_encode($merged_cart), $user['id']]);

            $_SESSION['cart'] = $merged_cart;
            setcookie('guest_cart', '', time() - 3600, "/");
        } else {
            $_SESSION['cart'] = $db_cart;
        }

        unset($_SESSION['auth_email']);
        header("Location: ../shop/index.php");
        exit;
    } else {
        $error = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aushadhi Platform</title>
    <style>
        :root {
            --primary: #2d5a27;
            --accent: #8ba888;
            --bg: #f9fbf9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 380px;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h2 {
            color: var(--primary);
            margin: 0;
        }

        .header p {
            color: #777;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #444;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(45, 90, 39, 0.1);
        }

        button {
            width: 100%;
            padding: 0.8rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background: #1e3d1a;
        }

        .error {
            background: #fff0f0;
            color: #d93025;
            padding: 0.7rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid #ffdada;
        }

        .footer-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #666;
        }

        .footer-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    
    <div class="login-card">
        <div class="header">
            <h2>Welcome Back</h2>
            <?php if ($session_email): ?>
                <p>Logging in as <b><?php echo htmlspecialchars($session_email); ?></b></p>
            <?php else: ?>
                <p>Login to your Aushadhi account</p>
            <?php endif; ?>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <!-- This is now editable even if it has a value -->
                <input type="email" name="email" value="<?php echo htmlspecialchars($session_email); ?>"
                    placeholder="name@example.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required autofocus>
            </div>

            <button type="submit">Sign In</button>
        </form>

        <div class="footer-link">
            <?php if ($session_email): ?>
                Not you? <a href="login.php?action=clear">Use a different email</a>
            <?php else: ?>
                Don't have an account? <a href="signup.php">Create one</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>