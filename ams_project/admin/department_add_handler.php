<?php
require '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    header("Location: ../staff/staff_login.php");
    session_destroy();
    exit();
}
// Handle form submission for adding a new department
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $dept_name = $_POST['department_name'];
    $dept_desc = $_POST['department_description'];

    $tmt = mysqli_prepare($conn, "INSERT INTO department (department_name, department_description) VALUES (?, ?)");
    mysqli_stmt_bind_param($tmt, "ss", $dept_name, $dept_desc);
    $success = mysqli_stmt_execute($tmt);

    if($success){
        $_SESSION['success'] = "Successfully added $dept_name department";
        header('Location: department_list.php');
        exit();
    }
    else
    {
        $_SESSION['error'] = "Failed to add $dept_name department";
    }

}