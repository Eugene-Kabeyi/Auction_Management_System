<?php
include __DIR__ . '/../header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin' || $_SESSION['admin_level'] !== 'super_admin') {

    $_SESSION['error'] = "Please log in as a Super admin to access this page.";
    header('Location: ../staff/staff_login.php');
    session_destroy();
    
    exit();
}
include __DIR__ . '/../config.php';
?>

<head>
    <title>Admin List</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
    <link rel="icon" type="image/png" href="../uploads/favicon.png">
    
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
    
    <div class="outer_container">
    <h2>Admin List</h2>
     <a href="admin_dashboard.php" class="back" >Back to Dashboard </a>
    
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
                echo "<td>" .  ($admin['firstname']) . "</td>";
                echo "<td>" .  ($admin['secondname']) . "</td>";
                echo "<td>" .  ($admin['surname']) . "</td>";
                echo "<td>" .  ($admin['role_name']) . "</td>";
                echo "<td>" .  ($admin['phone_number']) . "</td>";
                echo "<td>" .  ($admin['email']) . "</td>";
                echo "<td>" .  ($admin['username']) . "</td>";
                echo "<td>" .  ($admin['admin_level']) . "</td>";
                echo "<td>
                  <a href='admin_edit.php?id=" .  ($admin['admin_id']) . "'>Edit</a>
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