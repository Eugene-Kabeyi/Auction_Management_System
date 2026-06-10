<?php
include __DIR__ . '/../header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin' && $_SESSION['admin_level'] !== 'super_admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
include __DIR__ . '/../config.php';
?>

<head>
    <title>Admin List</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
    
</head>

<body>  
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <div class="outer_container">
    <h2>Admin List</h2>
    <a href="add_admin.php" class="back">Add New Admin </a>
       
<div class="inner_container">
        <table>
            <tr>
                <th>First Name</th>
                <th>Second Name</th>
                <th>Surname</th>
                <th>Role</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Username</th>
                <th>Admin Level</th>
                <th>Actions</th>
            </tr>

            <?php
            /* Fetch admin members from the database */
            $stmt = mysqli_prepare($conn, "SELECT a.admin_id, a.firstname, a.secondname,a.admin_level, a.surname, a.email, a.username, a.phone_number, a.role_id, r.role_name FROM admin a JOIN roles r ON a.role_id = r.role_id WHERE a.admin_level != 'super_admin'"); // Exclude super_admins from the list
            mysqli_stmt_execute($stmt);
            $admin_members = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

            foreach ($admin_members as $admin) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($admin['firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['secondname']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['surname']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['role_name']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['phone_number']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['username']) . "</td>";
                echo "<td>" . htmlspecialchars($admin['admin_level']) . "</td>";
                echo "<td>
                  <a href='admin_edit.php?id=" . htmlspecialchars($admin['admin_id']) . "'>Edit</a>
                   </td>";
                echo "</tr>";
            }
            ?>
        </table>
        </div>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>


<script>
    var adminlevel = <?php echo json_encode($_SESSION['admin_level']); ?>;
    if (adminlevel !== 'super_admin') {
        document.querySelectorAll('a href="admin_edit.php"]').forEach(function (link) {
            link.style.display = 'none';
        });
</script>