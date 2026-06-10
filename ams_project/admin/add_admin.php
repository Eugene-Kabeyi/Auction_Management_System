<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin' && $_SESSION['admin_level'] !== 'super_admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
?>

<head>
    <title>Add New Admin</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
    

</head>
<?php if (!empty($_SESSION['success'])): ?>
    <div class="flash success">
        <?=  ($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="flash error">
        <?=  ($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<body>
    <div class="outer_container f_container">
        <h2>Add New Admin</h2>
        <a href="admin_list.php" class="back">← Back to Admin List</a>

        <form action="add_admin_handler.php" method="POST" onsubmit="return validateAdmin()">

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="secondname">Second Name:</label>
            <input type="text" id="secondname" name="secondname">

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>

            <label for="admin_level">Admin Level:</label>
            <select name="admin_level">
                <option value="">--Select Admin Level--</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="moderator">Moderator</option>
            </select>

            <label>Password</label>
            <input type="text" id="password" name="password" class="pass_w">
            <span class="hide_show" id="togglePassword" style="cursor:pointer;">Hide/Show</span>


            <button type="submit">Add Admin</button>

        </form>

    </div>
</body>
<script>
    // Toggle password visibility
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
    document.getElementById("togglePassword").addEventListener("click", togglePasswordVisibility);



    // MAIN CONTROLLER

    function validateAdmin() {

        if (!validateNames()) return false;
        if (!validateEmail()) return false;
        if (!validateUsername()) return false;
        if (!validatePhone()) return false;
        if (!validateAdminLevel()) return false;
        if (!validatePassword()) return false;

        return true;
    }

    // FIRST NAME
    function validateNames() {

        var fname = document.getElementById("firstname").value.trim();
        var sname = document.getElementById("surname").value.trim();

        if (fname.length == 0) {
            alert("First name is required");
            document.getElementById("firstname").focus();
            return false;
        }

        if (sname.length == 0) {
            alert("Surname is required");
            document.getElementById("surname").focus();
            return false;
        }

        return true;
    }

    // EMAIL VALIDATION (manual)
    function validateEmail() {

        var email = document.getElementById("email").value.trim();

        if (email.length == 0) {
            alert("Email is required");
            return false;
        }

        if (email.indexOf("@") == -1 || email.indexOf(".") == -1) {
            alert("Invalid email format");
            document.getElementById("email").focus();
            return false;
        }

        return true;
    }

    // USERNAME
    function validateUsername() {

        var username = document.getElementById("username").value.trim();

        if (username.length == 0) {
            alert("Username is required");
            return false;
        }

        if (username.length < 4) {
            alert("Username must be at least 4 characters");
            return false;
        }

        return true;
    }

    // PHONE NUMBER
    function validatePhone() {

        var phone = document.getElementById("phone_number").value.trim();

        if (phone.length == 0) {
            alert("Phone number is required");
            return false;
        }

        if (isNaN(phone)) {
            alert("Phone number must be numeric");
            return false;
        }

        if (phone.length != 9 && phone.length != 10) {
            alert("Phone number must be 9 or 10 digits");
            return false;
        }

        return true;
    }

    // ADMIN LEVEL
    function validateAdminLevel() {

        var index = document.getElementsByName("admin_level")[0].selectedIndex;

        if (index == 0) {
            alert("Please select admin level");
            return false;
        }

        return true;
    }

    //PASSWORD
    function validatePassword() {

        var pass = document.getElementById("password").value;

        if (pass.length == 0) {
            alert("Password is required");
            return false;
        }

        if (pass.length < 6) {
            alert("Password must be at least 6 characters");
            return false;
        }

        return true;
    }

</script>
<?php include __DIR__ . '/../footer.php'; ?>