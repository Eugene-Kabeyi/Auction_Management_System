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

// Handle form submission for adding a new role
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_name = $_POST['role_name'];
    $description = $_POST['description'];
    // Insert new role name and description into the database
    $stmt = $conn->prepare("INSERT INTO roles (role_name, role_description) VALUES (:role_name, :description)");
    $stmt->bindParam(':role_name', $role_name);
    $stmt->bindParam(':description', $description);
    $success = $stmt->execute();
    if ($success) {
        // Role added successfully, redirect to role list page
        header("Location: ../role.php");
        $_SESSION['success'] = "Role added successfully.";
        exit();
    } else {
        // Error occurred while adding role, display error message      
        $_SESSION['error'] = "Error adding role. Please try again.";
        $_SESSION['error'] = $stmt->errorInfo()[2]; // Get detailed error message from PDO
    }
}
?>