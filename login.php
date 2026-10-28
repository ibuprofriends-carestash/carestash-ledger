<?php
    session_start();
    require_once __DIR__ . '/config/database.php';

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and execute the statement using PDO
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        // Fetch the user record directly as an associative array
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user account with that email exists
        if (!$row) {
            echo "<script>alert('No existing account'); window.location='index.php';</script>";
            exit();
        } else {
            // Verify the hashed password
            if (password_verify($password, $row['password'])) {
                $_SESSION['USERID'] = $row['id'];
                $_SESSION['USERNAME'] = $row['full_name'];
                $_SESSION['ROLE'] = $row['role'];

                // Redirect based on user role
                if ($row['role'] == "Admin") {
                    header('location: admin/index.php');
                } else if ($row['role'] == "Doctor") {
                    header('location: doctors/index.php');
                } else if ($row['role'] == "patient" || $row['role'] == "User") {
                     header('location: patients/index.php');
                     exit();
                } else {
                    echo "<script>alert('Invalid role'); window.location='index.php';</script>";
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password'); window.location='index.php';</script>";
                exit();
            }
        }
    } else {
        header("location: index.php");
        exit();
    }
?>