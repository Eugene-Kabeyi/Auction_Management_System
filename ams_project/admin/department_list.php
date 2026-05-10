<?php
include __DIR__ . ('/../header.php');
if (!isset($_SESSION['user_id']) && ($_SESSION['login_type']) !== 'admin') {
    $_SESSION['error'] = "Please login as admin to access this window";
    header('Location: ../staff/staff_login.php');
    session_destroy();
    exit();
}

include __DIR__ . ('/../config.php');
?>

<head>
    <title>Department List</title>
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
        <h2>Department List</h2>
        <a href="department_add.php" class="back">Add Department </a>
        <div class="inner-container">
            <table>
                <tr>

                    <th>Department Name:</th>
                    <th>Department Description</th>
                    <th>Actions</th>
                </tr>
                <?php
                $tmt = $conn->prepare("SELECT * FROM department");
                $tmt->execute();
                $departments = $tmt->fetchAll();

                foreach ($departments as $dept) {

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($dept['department_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($dept['department_description']) . "</td>";
                    echo "<td> <a href = 'department_edit.php?department_id=" . htmlspecialchars($dept['department_id']) . "'>Edit </a></td>";
                    echo "</tr>";
                }

                ?>
            </table>
        </div>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>