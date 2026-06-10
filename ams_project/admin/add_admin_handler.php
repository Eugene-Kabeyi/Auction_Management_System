<?php
require '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['admin_level'] !== 'super_admin') {
    $_SESSION['error'] = "Please log in as an super admin to access this page.";
    header("Location: ../staff/staff_login.php");
    session_destroy();
    exit();
}

// Handle form submission for adding a new admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $admin_level = $_POST['admin_level'];
    $password = $_POST['password'];

    try {

        $tmt = mysqli_prepare($conn, "INSERT INTO admin (firstname, secondname, surname, email, username, phone_number, admin_level, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($tmt, "ssssssss", $firstname, $secondname, $surname, $email, $username, $phone_number, $admin_level, $password);
        $success = mysqli_stmt_execute($tmt);
        if ($success) {
            $_SESSION['success'] = "Admin added successfully.";
            header("Location: admin_list.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to add admin. Please try again.";
            header("Location: add_admin.php?error");
            exit();

        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Unexpected error occurred. Please contact support if the issue persists.";
        header("Location: add_admin.php?error");
        exit();
    }


}