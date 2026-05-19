<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../log_activity.php';

if (isset($_SESSION['user_id']) && $_SESSION['login_type'] === 'admin') {
    // User is logged in and has the admin role, allow access to the page
} else {
    // User is not logged in or does not have the admin role, redirect to login page
    header("Location: ../staff/staff_login.php");
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Failed to access role edit page without admin privileges.");
    exit();
}
include __DIR__ . '/../config.php';
$role_id = $_GET['role_id'] ?? null;

$stmt = $conn->prepare("SELECT * FROM roles WHERE role_id = :role_id");
$stmt->execute(['role_id' => $role_id]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    die("Role not found");
}

?>


<head>
    <title>Edit Role Details</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">

</head>

<body>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <div class="outer_container f_container">
        <div class="f_inner_container">
            <h2>Edit Role</h2>
            <form action="edit_role_handler.php" method="POST" onsubmit="return validateRole();">
                <input type="hidden" name="role_id" value="<?php echo htmlspecialchars($role['role_id']); ?>">
                <label for="role_name">Role Name:</label>
                <input type="text" id="role_name" name="role_name"
                    value="<?php echo htmlspecialchars($role['role_name']); ?>">
                <label for="role_description">Role Description:</label>
                <textarea id="role_description"
                    name="role_description"><?php echo htmlspecialchars($role['role_description']); ?></textarea>
                <button type="submit" name="update_role">Update Role</button>
                <button type="submit" name="delete_role" value="delete" class="delete">Delete</button>

            </form>
        </div>
    </div>

    <script>
        function validateRole() {

            var role = document.getElementById("role_name").value.trim();

            if (role.length == 0) {
                alert("Role name is required");
                return false;
            }

            if (role.length < 3) {
                alert("Role name must be at least 3 characters");
                return false;
            }

            return true;
        }
    </script>
</body>
<?php
include __DIR__ . '/../footer.php';
?>