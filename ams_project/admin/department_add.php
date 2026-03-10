<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
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
        <h2>Add New Department</h2>
        <a href="department_list.php" class="back">Back to Department List</a>

        <form action="" method="POST">
           

            <label for="department_name">Department Name:</label>
            <input type="text" name="department_name" required>

            <label for="department_description">Department Description:</label>
            <textarea name="department_description" id=""></textarea>

            <button type="submit">Add Department</button>
        </form>
    </div>


</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $dept_name = $_POST['department_name'];
    $dept_desc = $_POST['department_description'];

    $tmt =$conn-> prepare("INSERT INTO department (department_name, department_description) VALUES(?, ?)");
    $success = $tmt-> execute([$dept_name,$dept_desc]);

    if($success){
        $_SESSION['success'] = "Successfully added $dept_name department";
        header('Location: department_list.php');
        exit();
    }
    else
    {
        $_SESSION['error'] = "Failed to add $dept_name department";
    }

}
