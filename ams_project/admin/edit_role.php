<?php
include __DIR__ . '/../header.php'; 

if(isset($_SESSION['user_id']) && $_SESSION['login_type'] === 'admin') {
    // User is logged in and has the admin role, allow access to the page
} else {
    // User is not logged in or does not have the admin role, redirect to login page
    header("Location: ../staff/staff_login.php");
    session_destroy();  
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
include __DIR__ . '/../config.php';
$role_id = $_GET['role_id'] ?? null;

$stmt = $conn->prepare("SELECT * FROM roles WHERE role_id = :role_id");
$stmt->execute(['role_id' => $role_id]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    die("Role not found");
}

?>

<head>
    <title>Edit Role Details</title>
    <link rel="stylesheet" href="admin_style.css">
   
</head> 
<body>
   <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?> 
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <div class="outer_container">
        <div class="f_inner_container">
            <h2>Edit Role</h2>
            <form action="" method="POST" onsubmit="return validateRole();">
                <input type="hidden" name="role_id" value="<?php echo htmlspecialchars($role['role_id']); ?>">
                <label for="role_name">Role Name:</label>
                <input type="text" id="role_name" name="role_name" value="<?php echo htmlspecialchars($role['role_name']); ?>" >
                <label for="role_description">Role Description:</label>
                <textarea id="role_description" name="role_description"><?php echo htmlspecialchars($role['role_description']); ?></textarea>
                <button type="submit" name="update_role">Update Role</button>
                <button type ="submit" name="delete_role" value="delete" class="delete">Delete</button>

            </form>
        </div>
    </div>

    <script>
function validateRole(){

    var role = document.getElementById("role_name").value.trim();

    if(role.length == 0){
        alert("Role name is required");
        return false;
    }

    if(role.length < 3){
        alert("Role name must be at least 3 characters");
        return false;
    }

    return true;
}
</script>
</body>
<?php
include __DIR__ . '/../footer.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];  
    $role_description = $_POST['role_description']; 
    // Update the role in the database

    if (isset($_POST['delete_role'])) {
         $tmt = $conn->prepare("DELETE FROM roles WHERE role_id = :role_id");
         $tmt->execute(['role_id' => $role_id]); 
         header("Location: role.php");
         exit();
    }elseif (isset($_POST['update_role'])) {
    $stmt = $conn->prepare("UPDATE roles SET role_name = :role_name, role_description = :role_description WHERE role_id = :role_id");
    $stmt->execute(['role_name' => $role_name, 'role_description' => $role_description, 'role_id' => $role_id]);
    // Redirect back to the roles list page after updating
    header("Location: role.php");
    exit();
    }else {
        // Invalid form submission
        $_SESSION['error'] = "Invalid form submission.";
      
    }
}
?>