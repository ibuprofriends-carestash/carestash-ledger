<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM patients WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Patient record deleted.'];
} else {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid record.'];
}

header('Location: dashboard.php');
exit;
