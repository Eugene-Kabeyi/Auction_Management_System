<?php
session_start();
include 'config.php'; 
include 'log_activity.php';

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
            if ($user === false) {
                $user = null; // No user found
                $error = "Invalid username.";
            }


            if ($user !== null && $password !== $user['password_hash']) {
                $error = "Invalid password.";
                $_SESSION['error'] = $error;
            }


            if ($user && $password == $user['password_hash']) {
                if ($loginType === 'admin') {
                    $_SESSION['user_id'] = $user['admin_id'];
                    $_SESSION['admin_level'] = $user['admin_level']; // Store admin level in session    
                } elseif ($loginType === 'staff') {
                    $_SESSION['user_id'] = $user['staff_id'];
                } else {
                    $_SESSION['user_id'] = $user['UID'];
                }

                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['f_name'] = $user['firstname'];
                $_SESSION['l_name'] = $user['surname'];
                $_SESSION['image_path'] = $user['image_path'];
                $_SESSION['login_type'] = $loginType; // Store login type in session

                $_SESSION['success'] = "Login successful! Welcome, " . htmlspecialchars($user['firstname']) . ".";
                if ($loginType === 'admin') {
                    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Admin logged in");
                    header("Location: ../ams_project/admin/admin_dashboard.php");
                } elseif ($loginType === 'staff') {
                    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Staff logged in");
                    header("Location: ../ams_project/staff/staff_dashboard.php");
                } else {
                    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "User logged in");
                    header("Location: ../ams_project/users/user_dashboard.php");
                }
                exit(); // very important


            } else {
                $_SESSION['error'] = $error;
                if ($loginType === 'staff') {
                    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed staff login attempt: " . $error);
                    header("Location: ../ams_project/staff/staff_login.php?error=1");
                    exit();
                } elseif ($loginType === 'admin') {
                    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed admin login attempt: " . $error);
                    header("Location: ../ams_project/staff/staff_login.php?error=1");
                    exit();
                } else {
                    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed user login attempt: " . $error);
                    header("Location: ../ams_project/users/login.php?error=1");
                    exit();
                }
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Database error during login: " . $error);
            $_SESSION['error'] = $error;
        }
    }
}
?>