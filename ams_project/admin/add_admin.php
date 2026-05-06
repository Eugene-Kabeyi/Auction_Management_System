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
    <link rel="stylesheet" href="admin_style.css">
    <style>
    

        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: center;
            margin: 20px auto;
            min-width: 640px;

        }

        .f_inner_container {
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .s_inner_container {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form {

            display: flex;
            flex-direction: column;
            gap: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
        }

        form label {
            font-weight: bold;
        }

        form input,
        form textarea,
        form select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 10px;
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;

        }

        form button:hover {
            background-color: #ffffff;
            color: #000000;
            border: solid #1f2933;
        }

        /* Password text to be disc */
        .pass_w {
            -webkit-text-security: disc;
            font-size: 16px;
            letter-spacing: 2px;
        }

        /* Hide/Show Password inside the password field */
        .hide_show {
            font-size: 12px;
            position: relative;
            top: -32px;
            left: 140px;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }


        .outer_container .back {
            border-radius: 5px;
            color: #ffffff;
            text-decoration: none;
            background-color: #1f2933;
            padding: 6px 0 6px 4px;
            width: 30%;
        }   
    </style>

</head>
<?php if (!empty($_SESSION['success'])): ?>
    <div class="flash success">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="flash error">
        <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<body>
    <div class="outer_container">
        <h2>Add New Admin</h2>
        <a href="admin_list.php" class="back">← Back to Admin List</a>

        <form action="" method="POST" onsubmit="return validateAdmin()">

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
            <span class="hide_show" id="togglePassword">Hide/Show</span>


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
<?php
// Handle form submission for adding a new admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $admin_level = $_POST['admin_level'];
    $password = $_POST['password'];

    try {

        $tmt = $conn->prepare("INSERT INTO admin (firstname, secondname, surname, email, username, phone_number, admin_level, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $tmt->execute([$firstname, $secondname, $surname, $email, $username, $phone_number, $admin_level, $password]);
    } catch (PDOException $e) {
        $_SESSION['error'] = "Unexpected error occurred. Please contact support if the issue persists.";
        header("Location: add_admin.php?error");
        exit();
    }

    if ($success) {
        $_SESSION['success'] = "Admin added successfully.";
        header("Location: admin_list.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add admin. Please try again.";
        header("Location: add_admin.php?error");
        exit();

    }
}