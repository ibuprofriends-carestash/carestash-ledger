<?php
require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
