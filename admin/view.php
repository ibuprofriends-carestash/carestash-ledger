<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$page_title = 'View Record';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Record not found.'];
    header('Location: dashboard.php');
    exit;
}

function row($label, $value) {
    echo '<div class="col-md-6 mb-3"><div class="text-muted small">' . htmlspecialchars($label) . '</div>';
    echo '<div>' . (trim((string)$value) !== '' ? nl2br(htmlspecialchars($value)) : '<span class="text-muted">&mdash;</span>') . '</div></div>';
}

include __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Patient Record #<?= $p['id'] ?></h3>
    <div>
        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Back to List</a>
    </div>
</div>

<div class="card p-4">
    <div class="row">
        <?php
        row('Full Name', $p['full_name']);
        row('Date of Birth', $p['date_of_birth']);
        row('Gender', $p['gender']);
        row('Contact Number', $p['contact_number']);
        row('Blood Type', $p['blood_type']);
        row('Admission Date', $p['admission_date']);
        row('Address', $p['address']);
        row('Attending Doctor', $p['doctor_name']);
        row('Diagnosis', $p['diagnosis']);
        row('Allergies', $p['allergies']);
        row('Medications', $p['medications']);
        row('Notes', $p['notes']);
        row('Record Created', $p['created_at']);
        row('Last Updated', $p['updated_at']);
        ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
