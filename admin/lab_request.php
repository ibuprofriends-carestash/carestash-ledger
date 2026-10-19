<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$requestId = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("
    SELECT lr.*, p.full_name, p.date_of_birth, p.gender, p.contact_number, p.blood_type, p.allergies
    FROM lab_requests lr
    INNER JOIN patients p ON p.id = lr.patient_id
    WHERE lr.id = ?
");
$stmt->execute([$requestId]);
$request = $stmt->fetch();

if (!$request) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Laboratory request not found.'];
    header('Location: dashboard.php');
    exit;
}

$tests = preg_split('/\r\n|\r|\n/', trim($request['tests']));
$page_title = 'Laboratory Request';
include __DIR__ . '/includes/header.php';
?>

<style>
    @media print {
        body { background: #fff !important; }
        .navbar, .print-actions { display: none !important; }
        .container { max-width: none !important; width: 100% !important; padding: 0 !important; }
        .request-sheet { border: 0 !important; box-shadow: none !important; }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3 print-actions">
    <a class="btn btn-outline-secondary" href="view.php?id=<?= (int)$request['patient_id'] ?>">Back to Patient</a>
    <button type="button" class="btn btn-primary" onclick="window.print()">Print Request</button>
</div>

<?php if (!empty($_SESSION['flash'])): $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> print-actions"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<article class="card request-sheet p-4 p-md-5">
    <div class="d-flex justify-content-between gap-3 border-bottom pb-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Laboratory Request</h1>
            <div class="text-muted">Request #<?= (int)$request['id'] ?></div>
        </div>
        <div class="text-end">
            <div><strong>Date:</strong> <?= htmlspecialchars($request['requested_at']) ?></div>
            <div><strong>Status:</strong> <?= htmlspecialchars($request['status']) ?></div>
        </div>
    </div>

    <h2 class="h5">Patient Details</h2>
    <div class="row mb-4">
        <div class="col-md-6 mb-2"><strong>Name:</strong> <?= htmlspecialchars($request['full_name']) ?></div>
        <div class="col-md-3 mb-2"><strong>Date of birth:</strong> <?= htmlspecialchars($request['date_of_birth']) ?></div>
        <div class="col-md-3 mb-2"><strong>Gender:</strong> <?= htmlspecialchars($request['gender']) ?></div>
        <div class="col-md-6 mb-2"><strong>Contact:</strong> <?= htmlspecialchars($request['contact_number'] ?: 'Not provided') ?></div>
        <div class="col-md-6 mb-2"><strong>Blood type:</strong> <?= htmlspecialchars($request['blood_type'] ?: 'Not provided') ?></div>
        <div class="col-12 mb-2"><strong>Known allergies:</strong> <?= htmlspecialchars($request['allergies'] ?: 'None recorded') ?></div>
    </div>

    <h2 class="h5">Consultation / Clinical Indication</h2>
    <div class="mb-4"><?= nl2br(htmlspecialchars($request['consultation'])) ?></div>

    <h2 class="h5">Requested Tests</h2>
    <ul class="mb-5">
        <?php foreach ($tests as $test): if (trim($test) !== ''): ?>
            <li><?= htmlspecialchars(trim($test)) ?></li>
        <?php endif; endforeach; ?>
    </ul>

    <div class="row mt-auto pt-5">
        <div class="col-md-6"><div class="border-top pt-2">Requesting clinician: <?= htmlspecialchars($request['requested_by']) ?></div></div>
        <div class="col-md-6"><div class="border-top pt-2">Signature / date</div></div>
    </div>
</article>

<?php include __DIR__ . '/includes/footer.php'; ?>