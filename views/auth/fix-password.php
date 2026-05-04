<?php
require '../../includes/db.php'; // Adjust path if needed

$new_password = 'admin123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'");
if ($stmt->execute([$hashed_password])) {
    echo "Password updated successfully! You can now login with: admin123";
} else {
    echo "Error updating password.";
}
?>
