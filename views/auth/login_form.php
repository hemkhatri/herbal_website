<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. DATABASE CONNECTION
// Using DOCUMENT_ROOT makes it work regardless of where this file is included
require_once $_SERVER['DOCUMENT_ROOT'] . '/aushadhi-platform/includes/db.php';

// 2. HANDLE AJAX POST REQUEST FIRST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // Clear any previous output buffers to ensure a clean response
    ob_clean(); 
    
    $email = $_POST['email'] ?? '';
    $_SESSION['auth_email'] = $email;

    if (!isset($pdo)) {
        echo "Error: Database connection failed.";
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Echo ONLY the URL and exit immediately
        if ($user) {
            echo "/aushadhi-platform/views/auth/login.php";
        } else {
            echo "/aushadhi-platform/views/auth/signup.php";
        }
        exit; 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

// 3. LOAD ENV VARIABLES
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
            list($name, $value) = explode('=', $line, 2);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            $_ENV[trim($name)] = $value;
        }
    }
}

// Try both paths to be safe
loadEnv(__DIR__ . '/../../.env');
loadEnv($_SERVER['DOCUMENT_ROOT'] . '/aushadhi-platform/.env');

$clientId = $_ENV['GOOGLE_CLIENT_ID'] ?? '';
?>

<!-- 4. HTML AND SCRIPTS (Only visible if not a POST request) -->

<!-- Correct Google Library URL -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<div class="flex flex-col items-center gap-4">
    <!-- Google Login Initialization -->
    <div id="g_id_onload" 
         data-client_id="<?php echo htmlspecialchars($clientId); ?>" 
         data-callback="handleCredentialResponse" 
         data-auto_prompt="false">
    </div>

    <!-- The Google Button UI -->
    <div class="g_id_signin" 
         data-type="standard" 
         data-shape="rectangular" 
         data-theme="outline" 
         data-text="continue_with" 
         data-size="large" 
         data-logo_alignment="left">
    </div>

    <div class="flex items-center w-full my-2">
        <hr class="flex-grow border-gray-300">
        <span class="px-2 text-gray-500 text-sm">OR</span>
        <hr class="flex-grow border-gray-300">
    </div>

    <!-- Email Form -->
    <form id="email-check-form" class="w-full space-y-4" onsubmit="handleEmailCheck(event)">
        <h2 class="text-2xl font-bold text-center mb-4 font-logo">Continue with Email</h2>
        <input type="email" name="email" required 
               class="w-full px-4 py-2 border rounded-xl" 
               placeholder="name@example.com">
        <button type="submit" 
                class="w-full bg-[#0d1117] text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition">
            Continue
        </button>
    </form>
</div>

<script>
// This runs when Google Login is successful
function handleCredentialResponse(response) {
    // Send the JWT token to your backend via POST
    const formData = new FormData();
    formData.append('credential', response.credential);

    fetch('/aushadhi-platform/views/auth/google_verify.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = "/aushadhi-platform/index.php"; // Redirect on success
        } else {
            alert("Login Failed: " + data.message);
        }
    })
    .catch(err => console.error("Error:", err));
}


// This handles your manual email check
window.handleEmailCheck = async function (e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    try {
        // Pointing to the file itself
        const response = await fetch('/aushadhi-platform/views/auth/login_form.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.text();
        const cleanUrl = result.trim();

        // Safety check: only redirect if the response looks like a URL
        if (cleanUrl.startsWith('/aushadhi-platform')) {
            window.location.href = cleanUrl;
        } else {
            console.error("Server Response:", cleanUrl);
            alert("Error: " + cleanUrl);
        }
    } catch (error) {
        console.error("Fetch error:", error);
    }
};
</script>
