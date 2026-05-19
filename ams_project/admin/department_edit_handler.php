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
include __DIR__ . '/../log_activity.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dept_id = $_POST['department_id'];
    $dept_name = $_POST['department_name'];
    $dept_desc = $_POST['department_description'];

    if (isset($_POST['update'])) {

        $stmt = $conn->prepare("UPDATE department SET department_name = ?, department_description = ? WHERE department_id = ?");
        $success = $stmt->execute([$dept_name, $dept_desc, $dept_id]);

        if ($success) {
            $_SESSION['success'] = "Department updated successfully!";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Updated department with ID: " . $dept_id);
        } else {
            $_SESSION['error'] = "An error occurred while updating.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to update department with ID: " . $dept_id);
        }

        header('Location: department_list.php');
        exit();

    } elseif (isset($_POST['delete'])) {

        $stmt = $conn->prepare("DELETE FROM department WHERE department_id = ?");

        $success = $stmt->execute([$dept_id]);

        if ($success) {
            $_SESSION['success'] = "Department deleted successfully!";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Deleted department with ID: " . $dept_id);
        } else {
            $_SESSION['error'] = "Failed to delete department.";
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to delete department with ID: " . $dept_id);
        }

        header('Location: department_list.php');
        exit();
    }

}
?>