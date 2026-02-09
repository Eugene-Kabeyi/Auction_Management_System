<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    // Redirect to login page if not logged in    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
} ?>

<head>
    <style>
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 80%;
            margin: 0 auto;
            justify-content: center;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
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

        td a {
            border-radius: 5px;
            color: #ffffff;
            text-decoration: none;
            background-color: #1f2933;
            padding: 6px 12px;
        }

        td a:hover {
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
    <div class="outer_container">
        <h2>Staff List</h2>

        <a href="staff_add.php" class="back">Add New Staff Member</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone Number</th>
                <th>Actions</th>
            </tr>

            <?php
            $stmt = $conn->prepare("SELECT s.staff_id, s.firstname, s.secondname, s.surname,s.email, r.role_name AS role, s.phone_number FROM staff s JOIN roles r ON s.role_id = r.role_id");
            $stmt->execute();
            $staff_members = $stmt->fetchAll();

            foreach ($staff_members as $staff) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($staff['staff_id']) . "</td>";
                echo "<td>" . htmlspecialchars($staff['firstname'] . ' ' . $staff['secondname'] . ' ' . $staff['surname']) . "</td>";
                echo "<td>" . htmlspecialchars($staff['email']) . "</td>";
                echo "<td>" . htmlspecialchars($staff['role']) . "</td>";
                echo "<td>" . htmlspecialchars($staff['phone_number']) . "</td>";
                echo "<td>
                    <a href='staff_edit.php?id=" . htmlspecialchars($staff['staff_id']) . "'>Edit</a>
                  </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

</body>

<?php include __DIR__ . '/../footer.php'; ?>