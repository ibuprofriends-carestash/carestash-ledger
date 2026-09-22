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
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #edf7ee, #f6fbff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .register-card {
            width: 100%;
            max-width: 520px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 16px;
        }
    </style>
</head>
<body>
    <div class="card register-card p-4">
        <h3 class="text-center mb-3">Create Account</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($form['full_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($form['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($form['username']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Create Account</button>
        </form>

        <div class="text-center mt-3">
            <p class="mb-0">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
