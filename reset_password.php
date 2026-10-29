<?php
session_start();
require_once __DIR__ . '/config/database.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';

if (empty($token)) {
    header("location: index.php");
    exit();
}

// Verify token validity and expiration
$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_expires_at > NOW()");
$stmt->execute([':token' => $token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $error = "The password reset link is invalid or has expired.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Hash new password and clear token fields
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expires_at = NULL WHERE id = :id");
        $update->execute([
            ':password' => $hashed,
            ':id'       => $user['id']
        ]);

        $success = "Your password has been successfully reset! You can now <a href='index.php'>sign in</a>.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/medical.css" rel="stylesheet">
</head>
<body class="medical-login-page">
    <main class="medical-login-card">
        <div class="text-center">
            <p class="text-success fw-semibold text-uppercase small mb-1">CareStash Health</p>
            <h1 class="h3 mb-2">Reset Password</h1>
            <p class="text-muted mb-4">Please enter your new password below.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php else: ?>
            <?php if ($user): ?>
            <!-- FIXED: Form action now points to reset_password.php with the underscore -->
            <form method="POST" action="reset_password.php?token=<?= htmlspecialchars($token) ?>">
                <div class="mb-3">
                    <label class="form-label" for="password">New Password</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="confirm_password">Confirm New Password</label>
                    <input id="confirm_password" type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Update Password</button>
            </form>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>