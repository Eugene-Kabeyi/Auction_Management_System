<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}



$dept_id = $_GET['department_id'];

// Fetch admin details
$stmt = $conn->prepare("SELECT * FROM department WHERE department_id = ?");
$stmt->execute([$department_id]);
$dept = $stmt->fetch(PDO::FETCH_ASSOC);


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
    <h2>Edit Departments</h2>
    <div class= "outer_container">
    <a href="department_list" class= "back">Back to department List:</a>
    <form action="" method="post">
        <input type="hidden" name="department_id" value="<?php echo htmlspecialchars($dept['department_id']) ?>">

        <label for="department_name">Department Name:</label>
        <input type="text" name="department_name" value="<?php echo htmlspecialchars($dept['department_name']) ?>"
            required>

        <label for="department_description">Department Description:</label>
        <input type="text" name="department_description"
            value="<?php echo htmlspecialchars($dept['department_description']) ?>">

        <button type="submit" name="update">Update Department</button>
        <button type="submit" class="delete" name="delete">Delete Department</button>


    </form>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'
    ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dept_id = $_POST['department_id'];
    $dept_name = $_POST['department_name'];
    $dept_desc = $_POST['department_description'];

    if (isset($_POST['update'])) {
        $tmt = $conn->prepare("UPDATE department SET department_name = ? , department_description = ? WHERE  department_id= ?");
        $success = $tmt->execute([$dept_name, $dept_desc, $dept_id]);
        if (!$success) {
            $_SESSION['error'] = "An error occurred while updating";
        }
        $_SESSION['success'] = "Department updated successfully!";
        header('Location : department_list.php');
        exit();
    } elseif (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM department WHERE department_id = ?");
        $success = $stmt->execute([$dept_id]);

        if (!$success) {
            $_SESSION['error'] = "Failed to delete department";
        } else {
            $_SESSION['success'] = "Department deleted successfully!";
        header('Location: department_list.php');
        exit();
        }
    }

}