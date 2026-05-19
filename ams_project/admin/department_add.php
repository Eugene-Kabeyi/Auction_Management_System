<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}

?>

<head>
    <title>Add New Department</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">

</head>

<body>
    <div class="outer_container f_container">
         <?php if (!empty($_SESSION['success'])): ?>
            <div class="flash success">
                <?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <h2>Add New Department</h2>
        <a href="department_list.php" class="back">Back to Department List</a>

        <form action="department_add_handler.php" method="POST" onsubmit="return validateDepartment()">
           

            <label for="department_name">Department Name:</label>
            <input type="text" name="department_name" required>

            <label for="department_description">Department Description:</label>
            <textarea name="department_description" id=""></textarea>

            <button type="submit">Add Department</button>
        </form>
    </div>


</body>
<?php include __DIR__ . '/../footer.php'; ?>
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


