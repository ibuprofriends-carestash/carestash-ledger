<?php
session_start();

// Ensure the user is logged in and has a patient/user role
if (!isset($_SESSION['USERID']) || ($_SESSION['ROLE'] !== 'Patient' && $_SESSION['ROLE'] !== 'User')) {
    header("location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/medical.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">CareStash Health</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small">
                    Logged in as: <strong><?= ucwords(strtolower(htmlspecialchars($_SESSION['USERNAME'] ?? 'Patient'))) ?></strong>
                </span>
                <a href="../profile.php" class="btn btn-outline-light btn-sm me-2">Account Settings</a>
                <a href="../logout.php" class="btn btn-outline-light btn-sm">Sign out</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h3 text-success mb-2">Welcome to your Patient Portal</h1>
                        <p class="text-muted mb-4">Manage your health records, view medical history, and track prescriptions securely.</p>
                        
                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <h5 class="h6 text-dark fw-bold">My Medical Records</h5>
                                    <p class="text-muted small mb-2">View your clinical history and notes.</p>
                                    <a href="#" class="btn btn-sm btn-outline-success">View Records</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <h5 class="h6 text-dark fw-bold">Prescriptions</h5>
                                    <p class="text-muted small mb-2">Check active medication profiles.</p>
                                    <a href="#" class="btn btn-sm btn-outline-success">View Prescriptions</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <h5 class="h6 text-dark fw-bold">Appointments</h5>
                                    <p class="text-muted small mb-2">Check upcoming doctor visits.</p>
                                    <a href="#" class="btn btn-sm btn-outline-success">View Schedule</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>