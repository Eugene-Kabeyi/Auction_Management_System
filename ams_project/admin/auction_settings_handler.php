<?php
require __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tax_rate = $_POST['tax_rate'];
    $commission_rate = $_POST['commission_rate'];
    $stmt = mysqli_prepare($conn, "
        UPDATE auction_settings
        SET tax_rate = ?, commission_rate = ?
        WHERE id = 1
    ");
    mysqli_stmt_bind_param($stmt, "dd", $tax_rate, $commission_rate);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        $_SESSION['success'] = "Auction settings updated successfully";
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to update auction settings";
        header('Location: admin_dashboard.php');
        exit();
    }
}
