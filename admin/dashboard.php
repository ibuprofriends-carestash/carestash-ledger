<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$page_title = 'Dashboard';

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE full_name LIKE ? ORDER BY created_at DESC");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM patients ORDER BY created_at DESC");
}
$patients = $stmt->fetchAll();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

include __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Patient Records</h3>
    <a href="create.php" class="btn btn-success">+ Add New Record</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="card p-3 mb-3">
    <form method="GET" action="dashboard.php" class="row g-2">
        <div class="col-sm-8 col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search by patient name..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        <?php if ($search !== ''): ?>
        <div class="col-auto">
            <a href="dashboard.php" class="btn btn-outline-secondary">Clear</a>
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
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Blood Type</th>
                    <th>Doctor</th>
                    <th>Admission Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($patients) === 0): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">No patient records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($patients as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['full_name']) ?></td>
                        <td><?= htmlspecialchars($p['date_of_birth']) ?></td>
                        <td><?= htmlspecialchars($p['gender']) ?></td>
                        <td><?= htmlspecialchars($p['contact_number']) ?></td>
                        <td><?= htmlspecialchars($p['blood_type']) ?></td>
                        <td><?= htmlspecialchars($p['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($p['admission_date']) ?></td>
                        <td class="text-end">
                            <a href="view.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this record for <?= htmlspecialchars(addslashes($p['full_name'])) ?>? This cannot be undone.');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
