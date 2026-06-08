<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect form data
    $national_id = $_POST['national_id'];
    $business_id = !empty($_POST['business_id']) ? $_POST['business_id'] : NULL;
    $role_id = $_POST['role_id'];
    $firstname = $_POST['firstname'];
    $secondname = !empty($_POST['secondname']) ? $_POST['secondname'] : NULL;
    $surname = $_POST['surname'];
    $business_name = !empty($_POST['business_name']) ? $_POST['business_name'] : NULL;
    $phone = $_POST['phone_number'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Later use password_hash()

    $sql = "INSERT INTO users ( national_id, business_id, role_id, firstname, secondname, surname, business_name, phone_number, email, username, password_hash ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param( $stmt, "iiissssssss", $national_id, $business_id, $role_id, $firstname, $secondname, $surname, $business_name, $phone, $email, $username, $password );

        if (mysqli_stmt_execute($stmt)) {

            $_SESSION['success'] =
                "Successfully created account for " . $username;

            header("Location: ../ams_project/users/login.php");
            exit();

        } else {

            $_SESSION['error'] = "Registration failed.";

            header("Location: ../ams_project/users/register.php");
            exit();
        }

        mysqli_stmt_close($stmt);

    } else {

        $_SESSION['error'] = "Failed to prepare statement.";

        header("Location: ../ams_project/users/register.php");
        exit();
    }
}

mysqli_close($conn);
?>