<?php
session_start();
require "config.php";
include 'header.php';

// Check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['login_type'])) {
    die("Unauthorized access");
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['login_type'];

// Decide table + id column based on role
if ($role == "user") {

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE UID = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);

} elseif ($role == "staff") {

    $stmt = mysqli_prepare($conn, "SELECT * FROM staff WHERE staff_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);

} else {

    $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE admin_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
}

// Execute query
mysqli_stmt_execute($stmt);

// Get result
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
?>

<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="css/form_table_styles.css">
    <style>
        .back {
            display: inline-block;
            margin: auto;
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>



    <div class="outer_container f_container">

        <form id="profileForm" action="handle_profile_update.php" method="post" onsubmit="return validateForm()">
            <!-- Back button to dashboard based on role -->
            <?php if ($role == "user"): ?>
                <a href="users/user_dashboard.php" class="back">Back to Dashboard</a>
            <?php elseif ($role == "staff"): ?>
                <a href="staff/staff_dashboard.php" class="back">Back to Dashboard</a>
            <?php elseif ($role == "admin"): ?>
                <a href="admin/admin_dashboard.php" class="back">Back to Dashboard</a>
            <?php endif; ?>
            <h2>User Profile</h2>
            <label>First Name:</label>
            <input type="text" name="firstname" class="firstname" id="firstname"
                value="<?php echo ($user['firstname'] ?? ''); ?>">

            <label>Last Name:</label>
            <input type="text" name="surname" class="surname" id="surname"
                value="<?php echo ($user['surname'] ?? ''); ?>">

            <label>Phone Number:</label>
            <input type="text" name="phone_number" class="phone_number" id="phone_number"
                value="<?php echo ($user['phone_number'] ?? ''); ?>">

            <label>Email:</label>
            <input type="text" name="email" class="email" id="email" value="<?php echo ($user['email'] ?? ''); ?>">

            <label>Username:</label>
            <input type="text" name="username" class="username" id="username"
                value="<?php echo ($user['username'] ?? ''); ?>">

            <label>Password:</label>
            <input type="text" name="password" class="password" id="password">

            <label>Confirm Password:</label>
            <input type="text" name="confirm_password" class="confirm_password" id="confirm_password">

            <button type="submit">Update Profile</button>

        </form>

    </div>

    <form id="deleteForm" action="handle_delete_account.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <button type="button" id="deleteBtn">
            Delete Account
        </button>
    </form>
    <script>
        function validateForm() {

            let firstname = document.getElementById("firstname").value.trim();
            let surname = document.getElementById("surname").value.trim();
            let email = document.getElementById("email").value.trim();
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value;
            let confirm_password = document.getElementById("confirm_password").value;

            if (firstname === "" || surname === "" || email === "" || username === "") {
                alert("Please fill in all required fields.");
                return false;
            }

            if (email.indexOf("@") === -1) {
                alert("Invalid email format. Email must contain @");
                return false;
            }

            if (password !== "" || confirm_password !== "") {

                if (password === "" || confirm_password === "") {
                    alert("Please fill both password fields.");
                    return false;
                }

                if (password !== confirm_password) {
                    alert("Passwords do not match.");
                    return false;
                }

                if (password.length < 6) {
                    alert("Password must be at least 6 characters.");
                    return false;
                }
            }

            return true;
        }
    </script>

</body>