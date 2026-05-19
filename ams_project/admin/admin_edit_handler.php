<?php
require '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../log_activity.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    $_SESSION['error'] = "Please log in as an super admin to access this page.";
    header("Location: ../staff/staff_login.php");
    session_destroy();
    exit();
}
// Update and delete functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $admin_level = $_POST['admin_level'];
    $admin_id = $_POST['admin_id'];

    // Update admin details
    if(isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE admin SET firstname = ?, secondname = ?, surname = ?, email = ?, username = ?, phone_number = ?, admin_level = ? WHERE admin_id = ?");
        $success = $stmt->execute([$firstname, $secondname, $surname, $email, $username, $phone_number, $admin_level, $admin_id]);
        if ($success) {
            $_SESSION['success'] = "Admin details updated successfully.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Updated admin with ID: " . $admin_id);
        }else {
            $_SESSION['error'] = "Failed to update admin details.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to update admin with ID: " . $admin_id);
        }
        header("Location: admin_edit.php?id=" . $admin_id);
            exit();
    }
    else if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM admin WHERE admin_id = ?");
        $success = $stmt->execute([$admin_id]);
        if ($success) {
            $_SESSION['success'] = "Admin deleted successfully.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Deleted admin with ID: " . $admin_id);
        } else {
            $_SESSION['error'] = "Failed to delete admin.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to delete admin with ID: " . $admin_id);
        }
        header("Location: /admin_list.php");
        exit();
    }
     else {
        $_SESSION['error'] = "Invalid form submission.";
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Invalid form submission while editing admin with ID: " . $admin_id);
        header("Location: /admin_edit.php?id=" . $admin_id);
        exit();     
     }
    
}?>