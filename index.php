<?php
session_start();
require_once __DIR__ . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/medical.css" rel="stylesheet">
    <script src="assets/js/medical-theme.js" defer></script>
</head>
<body class="medical-login-page">
    <main class="medical-login-card">
        <div class="text-center">
            <p class="text-success fw-semibold text-uppercase small mb-1">CareStash Health</p>
            <h1 class="h3 mb-2">Sign in to your account</h1>
            <p class="text-muted mb-4">Welcome! Please enter your credentials.</p>
        </div>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <input id="email" type="email" name="email" class="form-control" autocomplete="email" required>
            </div>
            
            <div class="mb-2">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" autocomplete="current-password" required>
            </div>

            <!-- Forgot Password Link -->
            <div class="mb-3 text-end">
                <a href="forgot_password.php" class="small text-success text-decoration-none">Forgot password?</a>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100 py-2">Sign in</button>
        </form>

        <div class="text-center mt-3">
            <p class="mb-0">Don't have an account? <a href="register.php">Create an account</a></p>
        </div>
        <div class="text-center mt-3">
            <a href="admin/login.php" class="small text-decoration-none">Administrator sign in</a>
        </div>
    </main>
</body>
</html>