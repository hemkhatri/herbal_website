<?php
session_start(); // <--- ADD THIS LINE HERE!

// login_form.php
require_once __DIR__ . '/../../includes/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    // Now this will actually save the email for login.php to see
    $_SESSION['auth_email'] = $email; 

    if (!isset($pdo)) {
        die("Database connection variable \$pdo not found.");
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            echo "/aushadhi-platform/views/auth/login.php";
        } else {
            echo "/aushadhi-platform/views/auth/signup.php";
        }
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>



<form id="email-check-form" class="space-y-4" onsubmit="handleEmailCheck(event)">
    <h2 class="text-2xl font-bold text-center mb-4 font-logo">Continue with Email</h2>
    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-xl" placeholder="name@example.com">
    <button type="submit" class="w-full bg-[#0d1117] text-white py-3 rounded-xl">Continue</button>
</form>

<script>
    window.handleEmailCheck = async function (e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        try {
            const response = await fetch('/aushadhi-platform/views/auth/login_form.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text();
            const cleanUrl = result.trim();

            // FIX: If the response contains '.php', it's a valid redirect
            if (cleanUrl.includes('.php')) {
                window.location.href = cleanUrl;
            } else {
                // Only alert if it's a real error (like a database crash)
                alert("System Message: " + cleanUrl);
            }
        } catch (error) {
            console.error("Fetch error:", error);
        }
    };

</script>