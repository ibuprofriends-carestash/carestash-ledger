<?php
session_start();
require_once __DIR__ . '/config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a secure random token
            $token = bin2hex(random_bytes(32));

            // Save token and set expiration to 1 hour via MySQL
            $update = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
            $update->execute([
                ':token'   => $token,
                ':email'   => $email
            ]);

            // Construct full reset link URL using your underscore filename
            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;

            // Display clickable link for local testing since XAMPP doesn't have an active mail server
            $message = "Password reset token generated successfully! <br><br><strong>Local Testing Link:</strong> <a href='$resetLink' class='alert-link' target='_blank'>Click here to reset your password</a>";
        } else {
            // Security best practice: don't reveal whether the email exists
            $message = "If that email exists in our system, a password reset link has been generated.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/medical.css" rel="stylesheet">
</head>
<body class="medical-login-page">
    <main class="medical-login-card">
        <div class="text-center">
            <p class="text-success fw-semibold text-uppercase small mb-1">CareStash Health</p>
            <h1 class="h3 mb-2">Forgot Password</h1>
            <p class="text-muted mb-4">Enter your email address to generate a password reset link.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="forgot_password.php">
            <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <input id="email" type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Generate Reset Link</button>
        </form>

        <div class="text-center mt-3">
            <p class="mb-0"><a href="index.php">Back to Sign in</a></p>
        </div>
    </main>
</body>
</html>