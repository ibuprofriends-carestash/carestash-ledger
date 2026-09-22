<?php
require_once __DIR__ . '/user_auth_check.php';
require_once __DIR__ . '/config/database.php';

$userId = (int)$_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, full_name, username, email, role, status, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Health Records</a>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white">Hi, <?= htmlspecialchars($_SESSION['user_username']) ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="mb-3">Welcome, <?= htmlspecialchars($user['full_name']) ?></h3>
                <p class="text-muted mb-4">Your account is active and ready to use.</p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <strong>Username</strong><br>
                            <?= htmlspecialchars($user['username']) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <strong>Email</strong><br>
                            <?= htmlspecialchars($user['email']) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <strong>Role</strong><br>
                            <?= htmlspecialchars($user['role']) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <strong>Status</strong><br>
                            <span class="badge bg-success"><?= htmlspecialchars($user['status']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
