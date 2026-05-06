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
$stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    header('Location: admin_list.php');
    exit();
}
?>
<head>
    <title>Edit Admin Details</title>
    <link rel="stylesheet" href="admin_style.css">
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
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
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
        form .delete {
            background-color: #ff4d4d;
            color: #ffffff;
        }
        form .delete:hover {
            background-color: #ffffff;
            color: #ff4d4d;
            border: 1px solid #ff4d4d;
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
            border: 1px solid #1f2933;
        }
        .outer_container .back {
            border-radius: 5px;
            color: #ffffff; 
            text-decoration: none;
            background-color: #1f2933;
            padding: 6px 0 6px 30px;
            width: 28%;
        }
    </style>

</head>
<body>
    <div class="outer_container">
        <h2>Edit Admin Details</h2>
        <a href="admin_list.php" class="back">Back to Admin List</a>
        <form action="" method="POST">
            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin['admin_id']); ?>">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($admin['firstname']); ?>" required>

            <label for="secondname">Second Name:</label>
            <input type="text" id="secondname" name="secondname" value="<?php echo htmlspecialchars($admin['secondname']); ?>">

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($admin['surname']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>

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
<?php
include __DIR__ . '/../footer.php';
?>
<?php
// Update and delete functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $admin_level = $_POST['admin_level'];
    $admin_id = $_POST['admin_id'];

    // Update admin details
    if(isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE admin SET firstname = ?, secondname = ?, surname = ?, email = ?, username = ?, phone_number = ?, admin_level = ? WHERE admin_id = ?");
        $stmt->execute([$firstname, $secondname, $surname, $email, $username, $phone_number, $admin_level, $admin_id]);
        $_SESSION['success'] = "Admin details updated successfully.";
        header("Location: admin_edit.php?id=" . $admin_id);
        exit();
    }
    else if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM admin WHERE admin_id = ?");
        $stmt->execute([$admin_id]);
        $_SESSION['success'] = "Admin deleted successfully.";
        header("Location: /admin_list.php");
        exit();
    }
     else {
        $_SESSION['error'] = "Invalid form submission.";
        header("Location: /admin_edit.php?id=" . $admin_id);
        exit();     
     }
    
}