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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $job_title = $_POST['job_title'];
    $department_id = $_POST['department_id'];
    $role_id = $_POST['role_id'];
    $employee_id = $_POST['employee_id'];
    $national_id = $_POST['national_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hire_date = $_POST['hire_date'];
    $employment_status = $_POST['employment_status'];
    //format date for database
    $date_parts = explode("/", $hire_date);
    if (count($date_parts) == 3) {
        $hire_date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];
    }else {
        $hire_date = null; // Invalid date format, set to null
    }




    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO staff (firstname, secondname, surname, email, phone_number, job_title, department_id, role_id, employee_id, national_id, username, password_hash, hire_date, employment_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $success = $stmt->execute([
        $firstname,
        $secondname,
        $surname,
        $email,
        $phone_number,
        $job_title,
        $department_id,
        $role_id,
        $employee_id,
        $national_id,
        $username,
        $password,
        $hire_date,
        $employment_status
    ]);
    if ($success) {
        $_SESSION['success'] = "Staff member added successfully!";
    } else {
        $_SESSION['error'] = "An error occurred while adding the staff member.";
    }
    // Redirect to staff list after successful addition
    header("Location: staff_list.php");
    exit();
}
?>