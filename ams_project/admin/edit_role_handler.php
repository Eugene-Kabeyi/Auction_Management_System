<?php
require '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../log_activity.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
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
    try{
    if (isset($_POST['delete_role'])) {

        $tmt = mysqli_prepare($conn, "DELETE FROM roles WHERE role_id = ?");
        mysqli_stmt_bind_param($tmt, "i", $role_id);
        $success = mysqli_stmt_execute($tmt);

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

        $stmt = mysqli_prepare($conn, "UPDATE roles SET role_name = ?, role_description = ? WHERE role_id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $role_name, $role_description, $role_id);
        $success = mysqli_stmt_execute($stmt);

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

    }}
    catch (Exception $e){
         // GLOBAL ERROR HANDLER
    $_SESSION['error'] = "Something went wrong. Please try again.";

    logActivity(
        $conn,
        $_SESSION['user_id'],
        $_SESSION['username'],
        "System error in role handler: " . $e->getMessage()
    );

    header("Location: role.php");
    exit();
    }
}
?>