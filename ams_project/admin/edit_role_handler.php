<?php
require '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../log_activity.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    header("Location: ../staff/staff_login.php");
    session_destroy();
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];
    $role_description = $_POST['role_description'];
    // Update the role in the database

    if (isset($_POST['delete_role'])) {
        $tmt = $conn->prepare("DELETE FROM roles WHERE role_id = :role_id");
        $success = $tmt->execute(['role_id' => $role_id]);
        if ($success) {
            $_SESSION['success'] = "Role deleted successfully.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Deleted role with ID: " . $role_id);
        } else {
            $_SESSION['error'] = "Failed to delete role.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to delete role with ID: " . $role_id);
        }
        header("Location: role.php");
        exit();
    } elseif (isset($_POST['update_role'])) {
        $stmt = $conn->prepare("UPDATE roles SET role_name = :role_name, role_description = :role_description WHERE role_id = :role_id");
        $success = $stmt->execute(['role_name' => $role_name, 'role_description' => $role_description, 'role_id' => $role_id]);
        if ($success) {
            $_SESSION['success'] = "Role updated successfully.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Updated role with ID: " . $role_id);
        } else {
            $_SESSION['error'] = "Failed to update role.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to update role with ID: " . $role_id);
        }
        // Redirect back to the roles list page after updating
        header("Location: role.php");
        exit();
    } else {
        // Invalid form submission
        $_SESSION['error'] = "Invalid form submission.";
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Invalid form submission while editing role with ID: " . $role_id);
        header("Location: role_edit.php?role_id=" . $role_id);
        exit();

    }
}
?>