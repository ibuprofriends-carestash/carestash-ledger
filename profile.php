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

// Fetch user account details
$stmt = $pdo->prepare("SELECT full_name, email, username, role FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['USERID']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle Password Update Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Fetch password hash for verification
    $passStmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
    $passStmt->execute([':id' => $_SESSION['USERID']]);
    $userData = $passStmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($current, $userData['password'])) {
        $error = "Incorrect current password.";
    } elseif (strlen($new) < 8) {
        $error = "New password must be at least 8 characters long.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $update->execute([
            ':password' => $hashed,
            ':id'       => $_SESSION['USERID']
        ]);
        $success = "Password successfully updated!";
    }
}

// Determine dashboard redirect link based on role
$role = $user['role'] ?? 'patient';
$dashboardLink = ($role === 'doctor') ? 'doctor/index.php' : (($role === 'admin') ? 'admin/index.php' : 'patient/index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/medical.css" rel="stylesheet">
    <script src="assets/js/medical-theme.js" defer></script>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= $dashboardLink ?>">CareStash Health</a>
            <div class="d-flex align-items-center">
                <a href="<?= $dashboardLink ?>" class="btn btn-outline-light btn-sm me-2">Back to Dashboard</a>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Sign out</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm border-0 p-4 mb-4">
            <h2 class="h4 text-success mb-3">Account Profile</h2>
            <hr>
            <div class="mb-2">
                <strong>Full Name:</strong> <?= htmlspecialchars($user['full_name']) ?>
            </div>
            <div class="mb-2">
                <strong>Email Address:</strong> <?= htmlspecialchars($user['email']) ?>
            </div>
            <div class="mb-2">
                <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?>
            </div>
            <div class="mb-2">
                <strong>Account Role:</strong> <span class="badge bg-success text-uppercase"><?= htmlspecialchars($user['role']) ?></span>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <h2 class="h4 text-success mb-3">Change Password</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="profile.php">
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
        </div>
    </div>

</body>
</html>