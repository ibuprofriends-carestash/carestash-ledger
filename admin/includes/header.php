<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>Health Records Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-brand { font-weight: 600; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .table thead { background-color: #eef2f7; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">🩺 Health Records Admin</a>
        <?php if (isset($_SESSION['admin_id'])): ?>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Records</a>
            <a href="users.php" class="btn btn-outline-light btn-sm">Users</a>
            <span class="text-white me-2">Hi, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container pb-5">
