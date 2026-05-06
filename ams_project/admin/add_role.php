<?php
include __DIR__ . '/../header.php'; 

if (isset($_SESSION['user_id']) && $_SESSION['login_type'] === 'admin') {
    // User is logged in and has the admin role, allow access to the page
} else {
    // User is not logged in or does not have the admin role, redirect to login page
    header("Location: ../staff/staff_login.php");
    session_destroy();  
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
include __DIR__ . '/../config.php';
?>

<head>
    <title>Add New Role</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
        
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 640px;
            margin: 0 auto;
            justify-content: center;
            margin-bottom: 200px;
            border: #2f373f 1px solid;
            padding: 20px;
            border-radius: 5px;
        }
        h2 {
            text-align: center;
            margin: 20px 0;
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


        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
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
        button.submit {
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        button.submit:hover {
            background-color: #ffffff;
            color: #1f2933;
            border: 1px solid #1f2933;
        }
        
    </style>
</head>

<body>
    <!-- Display success or error messages -->

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
        <h2>Add New Role</h2>
        <div class="outer_container">
            
                
                <form action="" method="POST" onsubmit="return validateRole()">
                    <label for="role_name">Role Name:</label>
                    <input type="text" id="role_name" name="role_name" required>
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                    <button type="submit" class="submit">Add Role</button>
                </form>
            
        </div>
</body>
<script>
        function validateRole() {
            const roleName = document.getElementById('role_name').value.trim();
            const description = document.getElementById('description').value.trim();

            if (roleName === '' || description === '') {
                alert('Please fill in all fields.');
                return false;
            }
            return true;
        }
</script>
<?php include __DIR__ . '/../footer.php'; ?>
<?php
// Handle form submission for adding a new role
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_name = $_POST['role_name'];
    $description = $_POST['description'];
    // Insert new role name and description into the database
  
    $stmt = $conn->prepare("INSERT INTO roles (role_name, role_description) VALUES (:role_name, :description)");
    $stmt->bindParam(':role_name', $role_name);
    $stmt->bindParam(':description', $description);
    $success = $stmt->execute();
    if ($success) {
        // Role added successfully, redirect to role list page
        header("Location: ../role.php");
        $_SESSION['success'] = "Role added successfully.";
        exit();
    } else {
        // Error occurred while adding role, display error message      
        $_SESSION['error'] = "Error adding role. Please try again.";
        $_SESSION['error'] = $stmt->errorInfo()[2]; // Get detailed error message from PDO
    }
}