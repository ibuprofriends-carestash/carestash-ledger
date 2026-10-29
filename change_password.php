<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Ensure user is logged in
if (!isset($_SESSION['USERID'])) {
    header("location: index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Fetch current user password hash from DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['USERID']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($current, $user['password'])) {
        $error = "Incorrect current password.";
    } elseif (strlen($new) < 8) {
        $error = "New password must be at least 8 characters.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $update->execute([
            ':password' => $hashed,
            ':id'       => $_SESSION['USERID']
        ]);
        $success = "Password successfully changed!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/medical.css" rel="stylesheet">
    <script src="assets/js/medical-theme.js" defer></script>
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow-sm border-0 p-4">
            <h2 class="h4 text-success mb-3">Change Password</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="change-password.php">
                <div class="mb-3">
                    <label class="form-label" for="current_password">Current Password</label>
                    <input id="current_password" type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="new_password">New Password</label>
                    <input id="new_password" type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <input id="confirm_password" type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Update Password</button>
            </form>
            <div class="mt-3 text-center">
                <?php 
                    $role = $_SESSION['ROLE'] ?? 'User';
                    $dash = ($role === 'Doctor') ? 'doctors/index.php' : (($role === 'admin') ? 'admin/index.php' : 'patients/index.php');
                ?>
                <a href="<?= $dash ?>" class="text-decoration-none">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>