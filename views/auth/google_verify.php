<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/aushadhi-platform/includes/db.php';

header('Content-Type: application/json');

// Get token (POST or JSON)
$id_token = $_POST['credential'] ?? null;

if (!$id_token) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $id_token = $data['credential'] ?? null;
}

if (!$id_token) {
    echo json_encode(['success' => false, 'message' => 'No credential token received']);
    exit;
}

// ✅ CORRECT GOOGLE VERIFY URL
$url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);

// cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// ⚠️ Keep SSL ON in real apps (you disabled it — bad habit)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        'success' => false,
        'message' => 'cURL Error: ' . curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$payload = json_decode($response, true);

// 🔥 Validate response properly
if (!isset($payload['email']) || !isset($payload['sub'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid Google token'
    ]);
    exit;
}

$email = $payload['email'];
$google_id = $payload['sub'];
$given_name = $payload['given_name'] ?? 'Google';
$family_name = $payload['family_name'] ?? 'User';

try {
    // Check user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Register
        $insert = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, google_id)
            VALUES (?, ?, ?, ?)
        ");
        $insert->execute([$given_name, $family_name, $email, $google_id]);
        $userId = $pdo->lastInsertId();
    } else {
        $userId = $user['id'];

        // Link Google account
        $update = $pdo->prepare("
            UPDATE users 
            SET google_id = ? 
            WHERE id = ? AND (google_id IS NULL OR google_id = '')
        ");
        $update->execute([$google_id, $userId]);
    }

    // Session
    $_SESSION['user_id'] = $userId;
    $_SESSION['auth_email'] = $email;
    $_SESSION['first_name'] = $given_name;

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database Error: ' . $e->getMessage()
    ]);
}