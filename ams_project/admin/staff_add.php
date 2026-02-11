<?php
include __DIR__ . "/../header.php";
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    // Redirect to login page if not logged in
    header('Location: ../staff/staff_login.php');
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
            width: 20%; 
            ;
        }


    </style>
</head>

<body>
    /<!-- ADD staff members TO the database(username,                | national_id       
| employee_id       
| role_id           
| department_id     
| firstname         
| secondname        
| surname           
| job_title         
| phone_number      
| email            
| username        
| password_hash    
| hire_date         
| employment_status | enum('active','on_leave','terminated','suspended') )*/-->
    <div class="outer_container">
        <h2>Add New Staff Member</h2>
        <a href="staff_list.php" class="back">Back to Staff List</a>
        <form action="" method="POST">

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="secondname">Second Name:</label>
            <input type="text" id="secondname" name="secondname">

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number">

            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title">

            <label for="department_id">Department ID:</label>
            <input type="number" id="department_id" name="department_id">

            <label for="role_id">Role:</label>
            <?php
            $stmt = $conn->query("SELECT * FROM roles");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <select id="role_id" name="role_id" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['role_id'] ?>"><?= $role['role_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id">

            <label for="national_id">National ID:</label>
            <input type="text" id="national_id" name="national_id">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="hire_date">Hire Date:</label>
            <input type="date" id="hire_date" name="hire_date">

            <label for="employment_status">Employment Status:</label>
            <select id="employment_status" name="employment_status">
                <option value="active">Active</option>
                <option value="on_leave">On Leave</option>
                <option value="terminated">Terminated</option>
                <option value="suspended">Suspended</option>
            </select>

            <label for="department">Department:</label>
            <?php
            $stmt = $conn->query("SELECT * FROM department");
            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <select id="department" name="department_id" required>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['department_id'] ?>"><?= $department['department_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Add Staff Member</button>
        </form>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $job_title = $_POST['job_title'];
    $department_id = $_POST['department_id'];
    $role_id = $_POST['role_id'];
    $employee_id = $_POST['employee_id'];
    $national_id = $_POST['national_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hire_date = $_POST['hire_date'];
    $employment_status = $_POST['employment_status'];

    

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO staff (firstname, secondname, surname, email, phone_number, job_title, department_id, role_id, employee_id, national_id, username, password_hash, hire_date, employment_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $firstname,
        $secondname,
        $surname,
        $email,
        $phone_number,
        $job_title,
        $department_id,
        $role_id,
        $employee_id,
        $national_id,
        $username,
        $password,
        $hire_date,
        $employment_status
    ]);
    // Redirect to staff list after successful addition
    header("Location: staff_list.php");
    exit();
}
?>
