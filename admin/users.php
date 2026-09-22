<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$page_title = 'Users';
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE full_name LIKE ? OR username LIKE ? OR email LIKE ? ORDER BY created_at DESC");
    $like = '%' . $search . '%';
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
}

$users = $stmt->fetchAll();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

include __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">User Accounts</h3>
    <a href="user_create.php" class="btn btn-success">+ Add User</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="card p-3 mb-3">
    <form method="GET" action="users.php" class="row g-2">
        <div class="col-sm-8 col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search by name, username, or email" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        <?php if ($search !== ''): ?>
        <div class="col-auto">
            <a href="users.php" class="btn btn-outline-secondary">Clear</a>
        </div>
        <?php endif; ?>
    </form>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) === 0): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= (int)$user['id'] ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($user['role']) ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?= $user['status'] === 'Active' ? 'success' : 'warning' ?>"><?= htmlspecialchars($user['status']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td class="text-end">
                            <a href="user_edit.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="user_delete.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete user <?= htmlspecialchars(addslashes($user['username'])) ?>?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
