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
?>

<head>
    <style>
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 640px;
            margin: 0 auto;
            justify-content: center;
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
    </style>
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
            <form action="" method="POST">
                <input type="hidden" name="role_id" value="<?php echo $_GET['id']; ?>">
                <label for="role_name">Role Name:</label>
                <input type="text" id="role_name" name="role_name" required>
                <button type="submit" name="update_role">Update Role</button>
                <button type ="submit" name="delete_role" value="delete" class="delete">Delete</button>
            </form>
        </div>
    </div>
<script>
    //show warning before deleting a role
    const deleteButton = document.querySelector('.delete');
    deleteButton.addEventListener('click', function(event) {
        const confirmDelete = confirm("Are you sure you want to delete this role? This action cannot be undone.");
        if (!confirmDelete) {
            event.preventDefault(); // Prevent form submission if user cancels
        }
    });
</script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];   
    // Update the role in the database

    if (isset($_POST['delete_role'])) {
         $tmt = $conn->prepare("DELETE FROM roles WHERE role_id = :role_id");
         $tmt->execute(['role_id' => $role_id]); 
         header("Location: role.php");
         exit();
    }elseif (isset($_POST['update_role'])) {
    $stmt = $conn->prepare("UPDATE roles SET role_name = :role_name WHERE role_id = :role_id");
    $stmt->execute(['role_name' => $role_name, 'role_id' => $role_id]);
    // Redirect back to the roles list page after updating
    header("Location: role.php");
    exit();
    }else {
        // Invalid form submission
        $_SESSION['error'] = "Invalid form submission.";
      
    }
}
?>