<?php
session_start();

// Ensure the user is logged in and has the doctor role
if (!isset($_SESSION['USERID']) || $_SESSION['ROLE'] !== 'Doctor') {
    header("location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Portal - CareStash Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/medical.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">CareStash Health | Doctor Portal</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small">
                    Dr. <strong><?= ucwords(strtolower(htmlspecialchars($_SESSION['USERNAME'] ?? 'Doctor'))) ?></strong>
                </span>
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
                        <h1 class="h3 text-success mb-2">Doctor Dashboard</h1>
                        <p class="text-muted mb-4">Review patient clinical profiles, manage medical care plans, and check your appointments.</p>
                        
                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <h5 class="h6 text-dark fw-bold">Patient Records</h5>
                                    <p class="text-muted small mb-2">Access patient histories and case files.</p>
                                    <a href="#" class="btn btn-sm btn-outline-success">View Patients</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <h5 class="h6 text-dark fw-bold">Prescription Manager</h5>
                                    <p class="text-muted small mb-2">Issue or update medication profiles.</p>
                                    <a href="#" class="btn btn-sm btn-outline-success">Manage Prescriptions</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <h5 class="h6 text-dark fw-bold">Appointment Schedule</h5>
                                    <p class="text-muted small mb-2">Check your upcoming clinic hours.</p>
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