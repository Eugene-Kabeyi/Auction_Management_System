<?php
include __DIR__ . '/../header.php';
if (!isset($_SESSION['user_id']) && $_SESSION['login_type'] !== 'admin') {
    // User is logged in and has the admin role, allow access to the page

    // User is not logged in or does not have the admin role, redirect to login page
    header("Location: ../staff/staff_login.php");
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
include __DIR__ . '/../config.php';
?>

<head>
    <title>Roles Management</title>
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

    <?php
    // Fetch all roles from the database, add, edit and delete
    require_once __DIR__ . '/../config.php';
    $stmt = $conn->prepare("SELECT * FROM roles");
    $stmt->execute();
    $roles = $stmt->fetchAll();
    ?>
    <div class="outer_container">
        <h2>Roles List</h2>
         <a href="add_role.php" class = "back">Add Role</a>
        <div class="inner_container">
        <table>
            <tr>
                <th>Role ID</th>
                <th>Role Name</th>
                <th>Role Description</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td><?php echo htmlspecialchars($role['role_id']); ?></td>
                    <td><?php echo htmlspecialchars($role['role_name'])?></td>
                    <td><?php echo htmlspecialchars($role['role_description']); ?></td>
                    <td>
                       
                        <a href="edit_role.php?role_id=<?php echo $role['role_id']; ?>">Edit</a ></td>
                </tr> 
            <?php endforeach; ?>
        </table>
        </div>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>