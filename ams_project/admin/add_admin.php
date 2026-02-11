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
        <h2>Add New Admin</h2>
        <a href="admin_list.php" class="back">Back to Admin List</a>

        <form action="" method="POST">

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="secondname">Second Name:</label>
            <input type="text" id="secondname" name="secondname">

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>

            <label for="admin_level">Admin Level:</label>
            <select name="admin_level">
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="moderator">Moderator</option>
            </select>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>


            <button type="submit">Add Admin</button>

        </form>

    </div>
</body>
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
    
    $tmt= $conn->prepare("INSERT INTO admin (firstname, secondname, surname, email, username, phone_number, admin_level, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $success=$tmt->execute([$firstname, $secondname, $surname, $email, $username, $phone_number, $admin_level, $password]);
    
    if($success) {
        $_SESSION['success'] = "Admin added successfully.";
        header("Location: admin_list.php");
        exit();
    } else {
       $_SESSION['error'] = "Failed to add admin. Please try again.";
       header("Location: add_admin.php?error");
       exit();
  
    }
}