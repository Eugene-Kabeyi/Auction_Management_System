<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    session_destroy();
    exit();
}



$dept_id = $_GET['department_id'];

// Fetch admin details
$stmt = $conn->prepare("SELECT * FROM department WHERE department_id = ?");
$stmt->execute([$dept_id]);
$dept = $stmt->fetch();
if (!$dept) {
    header('Location: department_list.php');
    $_SESSION['error'] = "Department not found.";
    exit();
}


?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dept_id = $_POST['department_id'];
    $dept_name = $_POST['department_name'];
    $dept_desc = $_POST['department_description'];

    if (isset($_POST['update'])) {

        $stmt = $conn->prepare("UPDATE department SET department_name = ?, department_description = ? WHERE department_id = ?");
        $success = $stmt->execute([$dept_name, $dept_desc, $dept_id]);

        if ($success) {
            $_SESSION['success'] = "Department updated successfully!";
        } else {
            $_SESSION['error'] = "An error occurred while updating.";
        }

        header('Location: department_list.php');
        exit();

    } elseif (isset($_POST['delete'])) {

        $stmt = $conn->prepare("DELETE FROM department WHERE department_id = ?");

        $success = $stmt->execute([$dept_id]);

        if ($success) {
            $_SESSION['success'] = "Department deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete department.";
        }

        header('Location: department_list.php');
        exit();
    }

}
?>
<head>
    <title>Edit Department Details</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">

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
        <form action="" method="post" onsubmit="return validateDepartment()">
            <input type="hidden" name="department_id" id="department_id" value="<?php echo htmlspecialchars($dept['department_id']) ?>">

            <label for="department_name">Department Name:</label>
            <input type="text" name="department_name" value="<?php echo htmlspecialchars($dept['department_name']) ?>"
                required>

            <label for="department_description">Department Description:</label>
            <!-- <input type="text" name="department_description"
            value="<?php //echo htmlspecialchars($dept['department_description']) ?>"> -->
            <textarea name="department_description"
                id="department_description"><?php echo htmlspecialchars($dept['department_description']) ?></textarea>

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
