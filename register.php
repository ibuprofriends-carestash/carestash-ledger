<?php
session_start();
require_once __DIR__ . '/config/database.php';

$errors = [];
$form = ['full_name' => '', 'email' => '', 'username' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['full_name'] = trim($_POST['full_name'] ?? '');
    $form['email']     = trim($_POST['email'] ?? '');
    $form['username']  = trim($_POST['username'] ?? '');
    $password          = $_POST['password'] ?? '';
    $confirm           = $_POST['confirm_password'] ?? '';

    // Validation
    if ($form['full_name'] === '') {
        $errors[] = 'Full name is required.';
    }
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (!preg_match('/^[A-Za-z0-9_.]{3,30}$/', $form['username'])) {
        $errors[] = 'Username must be 3-30 characters (letters, numbers, underscores, dots).';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // Check for existing email/username using PDO
    if (empty($errors)) {
        $check = $pdo->prepare("SELECT email, username FROM users WHERE email = :email OR username = :username");
        $check->execute([
            ':email'    => $form['email'],
            ':username' => $form['username']
        ]);
        $rows = $check->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            if (strcasecmp($row['email'], $form['email']) === 0) {
                $errors[] = 'That email is already registered.';
            }
            if (strcasecmp($row['username'], $form['username']) === 0) {
                $errors[] = 'That username is already taken.';
            }
        }
    }

    // Insert user with role automatically set to 'patient'
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $role = "User"; // Automatically set role to user

        $insert = $pdo->prepare("INSERT INTO users (full_name, email, username, password, role) VALUES (:full_name, :email, :username, :password, :role)");
        
        if ($insert->execute([
            ':full_name' => $form['full_name'],
            ':email'     => $form['email'],
            ':username'  => $form['username'],
            ':password'  => $hashed,
            ':role'      => $role
        ])) {
            echo "<script>alert('Account created! You can now sign in.'); window.location='index.php';</script>";
            exit();
        } else {
            $errors[] = 'Something went wrong while creating your account. Please try again.';
        }
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
            <p class="mb-0">Already have an account? <a href="index.php">Sign in</a></p>
        </div>
    </main>
</body>
</html>