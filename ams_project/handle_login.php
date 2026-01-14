<?php
// Start session at the top
session_start();

// Include database connection
include 'config.php';

// Initialize error variable
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            // Prepare SQL to fetch user by username or email
            $sql = "SELECT * FROM users WHERE username = :username OR email = :username";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify password
                if (password_verify($password, $user['password_hash'])) {
                    // Login successful, set session variables
                    $_SESSION['user_id']   = $user['UID'];
                    $_SESSION['username']  = $user['username'];
                    $_SESSION['email']     = $user['email'];
                    $_SESSION['role_id']   = $user['role_id'];
                    $_SESSION['firstname'] = $user['firstname'];
                    $_SESSION['loggedin']  = true;

                    // Update last login time (optional)
                    $update_sql = "UPDATE users SET last_login = NOW() WHERE UID = :uid";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->execute([':uid' => $user['UID']]);

                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Invalid password. Please try again.", $e;
                }
            } else {
                echo "Username or Email not found.", $e;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>


