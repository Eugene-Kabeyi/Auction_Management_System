<?php
// Start session at the top
session_start();

// Include database connection
include 'config.php';

// Initialize error variable
$error = '';

// Define role constants
define('ROLE_USER', 1);
define('ROLE_STAFF', 2);
define('ROLE_ADMIN', 3);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $loginType = $_POST['login_type'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        try {
            // Prepare SQL based on login type
            if ($loginType === 'staff') {
                $sql = "SELECT * FROM users WHERE username = :username AND role_id IN (:staff_role, :admin_role)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':staff_role', ROLE_STAFF, PDO::PARAM_INT);
                $stmt->bindValue(':admin_role', ROLE_ADMIN, PDO::PARAM_INT);
            } else {
                $sql = "SELECT * FROM users WHERE username = :username AND role_id = :user_role";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':user_role', ROLE_USER, PDO::PARAM_INT);
            }

            // Bind username parameter
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Fetch user
            $user = $stmt->fetch();

            // Verify password
            if ($user && $password === $user['password_hash']) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];

                // Redirect based on role
                if ($user['role_id'] == ROLE_ADMIN) {
                    header("Location: admin_dashboard.php");
                } elseif ($user['role_id'] == ROLE_STAFF) {
                    header("Location: staff_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>


