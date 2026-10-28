<?php
    require_once __DIR__ . '/config/database.php';
    session_start();
    session_unset();
    session_destroy();
    header("location: index.php");
    exit();
?>