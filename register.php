<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$form = [
    'full_name' => '',
    'email' => '',
    'username' => '',
    'password' => '',
    'confirm_password' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $key => $value) {
        $form[$key] = trim($_POST[$key] ?? '');
    }

    if ($form['full_name'] === '') {
        $errors[] = 'Full name is required.';
    }

    if ($form['email'] === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$form['email']]);
        if ($stmt->fetch()) {
            $errors[] = 'This email is already registered.';
        }
    }

    if ($form['username'] === '') {
        $errors[] = 'Username is required.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$form['username']]);
        if ($stmt->fetch()) {
            $errors[] = 'This username is already taken.';
        }
    }

    if ($form['password'] === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($form['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($form['confirm_password'] !== $form['password']) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($form['password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare(
            "INSERT INTO users (full_name, email, username, password, role, status) VALUES (?, ?, ?, ?, 'User', 'Active')"
        );
        $stmt->execute([
            $form['full_name'],
            $form['email'],
            $form['username'],
            $hashedPassword,
        ]);

        $_SESSION['flash_message'] = 'Registration successful. Please log in.';
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an account - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/medical.css" rel="stylesheet">
</head>
<body class="medical-login-page">
    <main class="medical-login-card">
        <div class="text-center">
            <p class="text-success fw-semibold text-uppercase small mb-1">CareStash Health</p>
            <h1 class="h3 mb-2">Create your account</h1>
            <p class="text-muted mb-4">Set up a secure account to access your health information.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="mb-3">
                <label class="form-label" for="full_name">Full name</label>
                <input id="full_name" type="text" name="full_name" class="form-control" autocomplete="name" value="<?= htmlspecialchars($form['full_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" autocomplete="email" value="<?= htmlspecialchars($form['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input id="username" type="text" name="username" class="form-control" autocomplete="username" value="<?= htmlspecialchars($form['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" autocomplete="new-password" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="confirm_password">Confirm password</label>
                <input id="confirm_password" type="password" name="confirm_password" class="form-control" autocomplete="new-password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Create account</button>
        </form>

        <div class="text-center mt-3">
            <p class="mb-0">Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </main>
</body>
</html>
