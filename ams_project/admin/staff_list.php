<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    // Redirect to login page if not logged in    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
} ?>

<head>
    <title>Staff List</title>
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
        <h2>Staff List</h2>
        <a href="admin_dashboard.php" class="back" >Back to Dashboard </a>

        <a href="staff_add.php" class="back">Add New Staff Member</a>
        <div class="inner_container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>

                <?php
                $stmt = mysqli_prepare($conn, "SELECT s.staff_id, s.firstname, s.secondname, s.surname,s.email, r.role_name AS role, s.phone_number FROM staff s JOIN roles r ON s.role_id = r.role_id");
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $staff_members = mysqli_fetch_all($result, MYSQLI_ASSOC);

                foreach ($staff_members as $staff) {
                    echo "<tr>";
                    echo "<td>" .  ($staff['staff_id']) . "</td>";
                    echo "<td>" .  ($staff['firstname'] . ' ' . $staff['secondname'] . ' ' . $staff['surname']) . "</td>";
                    echo "<td>" .  ($staff['email']) . "</td>";
                    echo "<td>" .  ($staff['role']) . "</td>";
                    echo "<td>" .  ($staff['phone_number']) . "</td>";
                    echo "<td><a href='staff_edit.php?id=" .  ($staff['staff_id']) . "'>Edit</a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>

</body>

<?php include __DIR__ . '/../footer.php'; ?>