<?php
include __DIR__ . ('/../header.php');
if (!isset($_SESSION['user_id']) && ($_SESSION['login_type']) !== 'admin') {
    $_SESSION['error'] = "Please login as admin to access this window";
    header('Location: ../staff/staff_login.php');
    session_destroy();
    exit();
}

include __DIR__ . ('/../config.php');
?>

<head>
    <style>
        .outer_container {
            max-width: 640px;
            display: flex;
            margin: 20px auto;
            justify-content: center;
            flex-direction: column;

        }

        h2 {
            text-align: center;
            padding: 20px auto;
        }

        table {

            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
        table a{
            margin-left: 10px;
            padding: 5px;
            text-decoration: none;
            background-color: #1c1d1d;
            color: #ffffff;
            font-weight: 400;
            border-radius: 4px;
            transition: 0.3s all ease;
        }

        table a:hover{
            background-color: #ffffff;
            border: 1px solid #1c1d1d;
            color: #1c1d1d;
        }
        .outer_container .back {
            border-radius: 5px;
            color: #ffffff;
            text-decoration: none;
            background-color: #1f2933;
            padding: 6px 0 6px 30px; 
            width: 30%; 
            margin: 20px 20px 20px 0;
        }

    </style>
</head>

<body>
    <div class="outer_container">
        <h2>Department List</h2>
        <a href="department_add.php" class="back">Add New Department </a>
        <table>
            <tr>

                <th>Department Name:</th>
                <th>Department Description</th>
                <th>Actions</th>
            </tr>
            <?php
            $tmt = $conn->prepare("SELECT * FROM department");
            $tmt->execute();
            $departments = $tmt->fetchAll();

            foreach ($departments as $dept) {

                echo "<tr>";
                echo "<td>" . htmlspecialchars($dept['department_name']) . "</td>";
                echo "<td>" . htmlspecialchars($dept['department_description']) . "</td>";
                echo "<td> <a href = 'department_edit.php?id=" . htmlspecialchars($dept['department_id']) . "'>Edit </a></td>";
                echo "</tr>";
            }

            ?>
        </table>
    </div>
</body>