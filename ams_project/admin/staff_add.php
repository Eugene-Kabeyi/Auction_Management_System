<?php
include __DIR__ . "/../header.php";
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
    
    <title>Add Staff Member</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
    <link rel="icon" type="image/png" href="../uploads/favicon.png">
    <style>
        #togglePassword {
            cursor: pointer;
            font-size: 10px;
            color: #7a7b7c;
            margin-left: 5px;
        }

        .pass_w {
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 3px;
            -webkit-text-security: disc;
        }
    </style>

</head>

<body>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?= ($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?= ($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <div class="outer_container f_container">
        <h2>Add New Staff Member</h2>
        <a href="staff_list.php" class="back">Back to Staff List</a>
        <form action="staff_add_handler.php" method="POST" onsubmit="return validateStaff()">

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname">

            <label for="secondname">Second Name:</label>
            <input type="text" id="secondname" name="secondname">

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname">

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" >

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number">


            <label for="department_id">Department ID:</label>
            <!--  -->
            <?php

            $tmt = mysqli_prepare($conn, "SELECT * FROM department");
            mysqli_stmt_execute($tmt);
            $departments = mysqli_fetch_all(mysqli_stmt_get_result($tmt), MYSQLI_ASSOC);

            ?>

            <select id="department_id" name="department_id">
                <option value="">--Select Department--</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['department_id'] ?>"><?= $department['department_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="role_id">Role:</label>
            <?php
            $stmt = mysqli_prepare($conn, "SELECT * FROM roles");
            mysqli_stmt_execute($stmt);
            $roles = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
            ?>
            <select id="role_id" name="role_id" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['role_id'] ?>"><?= $role['role_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id">

            <label for="national_id">National ID:</label>
            <input type="text" id="national_id" name="national_id">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" >

            <label for="password">Password:</label>
            <input type="text" id="password" name="password">
            <span id="togglePassword" onclick="togglePasswordVisibility()">Show/Hide</span>

            <label for="hire_date">Hire Date:</label>
            <input type="text" id="hire_date" name="hire_date">

            <label for="employment_status">Employment Status:</label>
            <select id="employment_status" name="employment_status">
                <option value="active">Active</option>
                <option value="on_leave">On Leave</option>
                <option value="terminated">Terminated</option>
                <option value="suspended">Suspended</option>
            </select>


            <button type="submit">Add Staff Member</button>
        </form>
    </div>
</body>
<script>
    function togglePasswordVisibility() {

        // Fetch the password field and the toggle text element
        var passwordField = document.getElementById("password");
        var toggleText = document.getElementById("togglePassword");

        // Toggle the class to switch between text and password styles
        if (passwordField.classList.contains("pass_w")) {
            passwordField.classList.remove("pass_w");
            toggleText.textContent = "Hide";
        } else {
            passwordField.classList.add("pass_w");
            toggleText.textContent = "Show";
        }
    }

    function validateStaff() {

        if (!validateNames()) return false;
        if (!validateEmail()) return false;
        if (!validatePhone()) return false;
        if (!validateIDs()) return false;
        if (!validateUsername()) return false;
        if (!validatePassword()) return false;
        if (!validateDate()) return false;
        if (!validateDropdowns()) return false;

        return true;
    }

    // NAMES
    function validateNames() {

        var fname = document.getElementById("firstname").value.trim();
        var sname = document.getElementById("surname").value.trim();

        if (fname.length == 0) {
            alert("First name is required");
            return false;
        }

        if (sname.length == 0) {
            alert("Surname is required");
            return false;
        }

        return true;
    }

    // EMAIL
    function validateEmail() {

        var email = document.getElementById("email").value;

        if (email.length == 0) {
            alert("Email is required");
            return false;
        }

        if (email.indexOf("@") == -1 || email.indexOf(".") == -1) {
            alert("Invalid email format");
            return false;
        }

        return true;
    }

    // PHONE
    function validatePhone() {

        var phone = document.getElementById("phone_number").value;

        if (phone.length == 0) {
            alert("Phone number is required");
            return false;
        }

        if (isNaN(phone)) {
            alert("Phone must be numeric");
            return false;
        }

        if (phone.length < 9) {
            alert("Phone number too short");
            return false;
        }

        return true;
    }

    // IDS
    function validateIDs() {

        var dept = document.getElementById("department_id").value;
        var national = document.getElementById("national_id").value;

        if (dept.length > 0 && isNaN(dept)) {
            alert("Department ID must be numeric");
            return false;
        }

        if (national.length > 0 && isNaN(national)) {
            alert("National ID must be numeric");
            return false;
        }

        return true;
    }

    // USERNAME
    function validateUsername() {

        var username = document.getElementById("username").value;

        if (username.length < 4) {
            alert("Username must be at least 4 characters");
            return false;
        }

        return true;
    }

    //////////////////////////////////////////////////
    // PASSWORD
    function validatePassword() {

        var pass = document.getElementById("password").value;

        if (pass.length < 6) {
            alert("Password must be at least 6 characters");
            return false;
        }

        return true;
    }

    // DATE
    function validateDate() {

        var date = document.getElementById("hire_date").value;

        if (date.length == 0) {
            alert("Hire date is required");
            return false;
        }

        if (date.indexOf("/") == -1) {
            alert("Date must be dd/mm/yyyy");
            return false;
        }

        var parts = date.split("/");

        if (parts.length != 3) {
            alert("Invalid date format");
            return false;
        }

        if (isNaN(parts[0]) || isNaN(parts[1]) || isNaN(parts[2])) {
            alert("Date must contain numbers only");
            return false;
        }
        //convert to date and make sure it is not in the future
        var hireDate = new Date(parts[2], parts[1] - 1, parts[0]);
        var today = new Date();
        if (hireDate > today) {
            alert("Hire date cannot be in the future");
            return false;
        }

        return true;


    }
    // DROPDOWNS
    function validateDropdowns() {

        var role = document.getElementById("role_id").selectedIndex;
        var dept = document.getElementById("department_id").selectedIndex;

        if (role == 0) {
            alert("Please select a role");
            return false;
        }

        if (dept == 0) {
            alert("Please select a department");
            return false;
        }

        return true;
    }

</script>
<?php include __DIR__ . '/../footer.php'; ?>