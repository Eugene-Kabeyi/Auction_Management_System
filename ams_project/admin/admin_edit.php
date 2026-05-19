<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin' && $_SESSION['admin_level'] !== 'super_admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_list.php');
    exit();
}

$admin_id = $_GET['id'];

// Fetch admin details
$stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ? AND admin_level != 'super_admin'");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    header('Location: admin_list.php');
    exit();
}
?>

<head>
    <title>Edit Admin Details</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
    

</head>
<body>
    <div class="outer_container f_container" >
        <h2>Edit Admin Details</h2>
        <a href="admin_list.php" class="back">Back </a>
        <form action="admin_edit_handler.php" method="POST" onsubmit="return validateAdmin()">
            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin['admin_id']); ?>">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($admin['firstname']); ?>" >

            <label for="secondname">Second Name:</label>
            <input type="text" id="secondname" name="secondname" value="<?php echo htmlspecialchars($admin['secondname']); ?>">

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($admin['surname']); ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" >

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" >

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($admin['phone_number']); ?>" required>

            <label for="admin_level">Admin Level:</label>
            <select name="admin_level" id="admin_level">
                <option value="super_admin" <?php if ($admin['admin_level'] === 'super_admin') echo 'selected'; ?>>Super Admin</option>
                <option value="admin" <?php if ($admin['admin_level'] === 'admin') echo 'selected'; ?>>Admin</option>
                <option value="moderator" <?php if ($admin['admin_level'] === 'moderator') echo 'selected'; ?>>Moderator</option>
            </select>

            <button type="submit" name="update">Update Admin</button>

      
            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin['admin_id']); ?>">
            <button type="submit" class="delete" name="delete">Delete Admin</button>
        </form>
        
    </div>
</body>
<script>
    function validateAdmin() {
        var firstname = document.getElementById("firstname").value.trim();
        var surname = document.getElementById("surname").value.trim();
        var email = document.getElementById("email").value.trim();
        var username = document.getElementById("username").value.trim();
        var phone_number = document.getElementById("phone_number").value.trim();

        if (firstname.length == 0) {
            alert("First name is required");
            return false;
        }
        if (surname.length == 0) {
            alert("Surname is required");
            return false;
        }
        if (email.length == 0) {
            alert("Email is required");
            return false;
        }
        if (username.length == 0) {
            alert("Username is required");
            return false;
        }
        if (phone_number.length == 0) {
            alert("Phone number is required");
            return false;
        }
        return true;
    }
</script>
<?php
include __DIR__ . '/../footer.php';
?>
