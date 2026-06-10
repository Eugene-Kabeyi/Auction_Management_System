<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    session_destroy();
    exit();
}



$dept_id = $_GET['department_id'];

// Fetch admin details
$stmt = mysqli_prepare($conn, "SELECT * FROM department WHERE department_id = ?");
mysqli_stmt_execute($stmt, [$dept_id]);
$dept = mysqli_stmt_get_result($stmt)->fetch_assoc();
if (!$dept) {
    header('Location: department_list.php');
    $_SESSION['error'] = "Department not found.";
    exit();
}


?>

<head>
    <title>Edit Department Details</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
    <link rel="icon" type="image/png" href="../uploads/favicon.png">

</head>

<body>
    <!-- Sucess and error messages -->
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
    <h2>Edit Departments</h2>
    <div class="outer_container f_container">
        <a href="department_list.php" class="back">Back to List:</a>
        <form action="department_edit_handler.php" method="post" onsubmit="return validateDepartment()">
            <input type="hidden" name="department_id" id="department_id" value="<?php echo  ($dept['department_id']) ?>">

            <label for="department_name">Department Name:</label>
            <input type="text" name="department_name" value="<?php echo  ($dept['department_name']) ?>"
                required>

            <label for="department_description">Department Description:</label>
            <!-- <input type="text" name="department_description"
            value="<?php //echo  ($dept['department_description']) ?>"> -->
            <textarea name="department_description"
                id="department_description"><?php echo  ($dept['department_description']) ?></textarea>

            <button type="submit" name="update">Update Department</button>
            <button type="submit" class="delete" name="delete" onclick="return confirm('Are you sure you want to delete this department?')">Delete Department</button>


        </form>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'
    ?>
<script>
    function validateDepartment() {
        var deptName = document.getElementById("department_name").value.trim();
        if (deptName.length == 0) {
            alert("Department name is required");
            return false;
        }
        if (deptName.length < 3) {
            alert("Department name must be at least 3 characters");
            return false;
        }
        return true;
    }
</script>
