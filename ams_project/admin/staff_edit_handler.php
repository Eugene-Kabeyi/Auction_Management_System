<?php
require '../config.php';
require '../log_activity.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    header("Location: ../staff/staff_login.php");
    session_destroy();
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_GET['id'];
    $employee_id = $_POST['employee_id'];
    $national_id = $_POST['national_id'];
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $job_title = $_POST['job_title'];
    $role_id = $_POST['role_id'];
    $employment_status = $_POST['employment_status'];
    try {
        // Update the staff member in the database  
        if (isset($_POST['delete_staff'])) {
            $stmt = mysqli_prepare($conn, "DELETE FROM staff WHERE staff_id = ?");
            $success = mysqli_stmt_execute($stmt, [$staff_id]);
            if ($success) {
                $_SESSION['success'] = "Staff member deleted successfully.";
                logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Deleted staff member with ID: " . $staff_id);
            } else {
                $_SESSION['error'] = "Failed to delete staff member.";
            }
            header("Location: staff_list.php");
            exit();
        } elseif (isset($_POST['update_staff'])) {

            $stmt = mysqli_prepare($conn, "UPDATE staff SET employee_id = ?, national_id = ?, firstname = ?, secondname = ?, surname = ?, email = ?, phone_number = ?, role_id = ?, employment_status = ? WHERE staff_id = ?");
            mysqli_stmt_bind_param($stmt, "sssssssssi", $employee_id, $national_id, $firstname, $secondname, $surname, $email, $phone_number,  $role_id, $employment_status, $staff_id);
            $success = mysqli_stmt_execute($stmt);

            if ($success) {
                $_SESSION['success'] = "Staff member updated successfully.";
                logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Updated staff member with ID: " . $staff_id);
            } else {
                $_SESSION['error'] = "Failed to update staff member.";
                logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to update staff member with ID: " . $staff_id);
            }
            header("Location: staff_list.php");
            exit();



        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Something went wrong. Please try again.";

        // Invalid form submission
        $_SESSION['error'] = "Invalid form submission.";
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Invalid form submission while editing staff member with ID: " . $staff_id);
        header("Location: staff_list.php");
        exit();

    }
}
?>