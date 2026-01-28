<?php
session_start();
include 'config.php'; // make sure the path is correct

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $loginType = $_POST['login_type'];

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        try {
            if ($loginType === 'staff') {
                $sql = "SELECT * FROM staff WHERE username = :username";
            } elseif ($loginType === 'admin') {
                $sql = "SELECT * FROM admin WHERE username = :username";
            } else {
                $sql = "SELECT * FROM users WHERE username = :username";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch();

            if ($user && $password === $user['password_hash']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];

                if ($user['role_id'] == 3) {
                    header("Location: admin_dashboard.php");
                } elseif ($user['role_id'] == 2) {
                    header("Location: staff_dashboard.php");
                } else {
                    header("Location: ../ams_project/users/user_dashboard.php");
                }
                exit(); // very important
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
