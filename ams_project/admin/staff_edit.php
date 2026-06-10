<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../log_activity.php';
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    // Redirect to login page if not logged in
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
include __DIR__ . '/../config.php';
?>


<head>
    <title>Edit Staff Member</title>
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
        <h2>Edit Staff Member</h2>

        <a href="staff_list.php" class="back">Back to Staff List</a>

        <?php

        $_GET['id'];

        $stmt = mysqli_prepare($conn, "SELECT s.staff_id, s.firstname, s.secondname, s.surname, s.username, s.national_id, s.hire_date, s.employment_status, s.job_title, s.email, s.employee_id, r.role_name AS role, s.phone_number FROM staff s JOIN roles r ON s.role_id = r.role_id WHERE s.staff_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
        mysqli_stmt_execute($stmt);
        $staff_members = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

        ?>

        <form action="staff_edit_handler.php" method="post">
            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id"
                value="<?php echo htmlspecialchars($staff_members[0]['employee_id'] ?? ''); ?>">
            <label for="national_id">National ID:</label>
            <input type="text" id="national_id" name="national_id"
                value="<?php echo htmlspecialchars($staff_members[0]['national_id'] ?? ''); ?>">

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname"
                value="<?php echo htmlspecialchars($staff_members[0]['firstname'] ?? ''); ?>">

            <label for="secondname">Second Name:</label>
            <input type="text" id="secondname" name="secondname"
                value="<?php echo htmlspecialchars($staff_members[0]['secondname'] ?? ''); ?>">

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname"
                value="<?php echo htmlspecialchars($staff_members[0]['surname'] ?? ''); ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email"
                value="<?php echo htmlspecialchars($staff_members[0]['email'] ?? ''); ?>">

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number"
                value="<?php echo htmlspecialchars($staff_members[0]['phone_number'] ?? ''); ?>">

            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title"
                value="<?php echo htmlspecialchars($staff_members[0]['job_title'] ?? ''); ?>">

            <label for="role">Role:</label>

            <?php $stmt = mysqli_prepare($conn, "SELECT * FROM roles");
            mysqli_stmt_execute($stmt);
            $roles = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
            ?>
            
            <select name="role_id" id="role_id">
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['role_id']; ?>" <?php echo (isset($staff_members[0]['role_id']) && $staff_members[0]['role_id'] == $role['role_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($role['role_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="employment_status">Employment Status:</label>
            <select id="employment_status" name="employment_status">
                <option value="active" <?php echo (isset($staff_members[0]['employment_status']) && $staff_members[0]['employment_status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="on_leave" <?php echo (isset($staff_members[0]['employment_status']) && $staff_members[0]['employment_status'] === 'on_leave') ? 'selected' : ''; ?>>On Leave</option>
                <option value="terminated" <?php echo (isset($staff_members[0]['employment_status']) && $staff_members[0]['employment_status'] === 'terminated') ? 'selected' : ''; ?>>Terminated</option>
                <option value="suspended" <?php echo (isset($staff_members[0]['employment_status']) && $staff_members[0]['employment_status'] === 'suspended') ? 'selected' : ''; ?>>Suspended</option>
            </select>

            <button type="submit" name="update_staff">Update Staff Member</button>
            <button type="submit" name="delete_staff" value="delete" class="delete"
                onclick="return confirm('Are you sure you want to delete this staff member?')">Delete Staff
                Member</button>
        </form>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>
<script>
    //show warning before deleting a staff member
    const deleteButton = document.querySelector('.delete');
    deleteButton.addEventListener('click', function (event) {
        const confirmDelete = confirm("Are you sure you want to delete this staff member? This action cannot be undone.");
        if (!confirmDelete) {
            event.preventDefault(); // Prevent form submission if user cancels
        }
    });
</script>