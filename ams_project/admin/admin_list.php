<?php
include __DIR__ . '/../header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin' && $_SESSION['admin_level'] !== 'super_admin') {
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
            width: 15%; 
            ;
        }
    </style>
</head>
<div class="outer_container">
    <h2>Admin List</h2>
    <a href="add_admin.php" class="back">Add New Admin </a>
    <!--| role_id       | int(11)                                 | NO   | MUL | NULL                |                |
| firstname     | varchar(255)                            | NO   |     | NULL                |                |
| secondname    | varchar(255)                            | YES  |     | NULL                |                |
| surname       | varchar(255)                            | NO   |     | NULL                |                |
| phone_number  | varchar(255)                            | NO   | UNI | NULL                |                |
| email         | varchar(255)                            | NO   | UNI | NULL                |                |
| username      | varchar(255)                            | NO   | UNI | NULL                |                |
| password_hash | varchar(255)                            | NO   |     | NULL                |                |
| admin_level   | enum('super_admin','admin','moderator') | YES  -->
    <table>
        <tr>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Surname</th>
            <th>Role</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Username</th>
            <th>Admin Level</th>
            <!-- <th>Actions</th> -->
        </tr>

        <?php
        /* Fetch admin members from the database */
        $stmt = $conn->query("SELECT a.admin_id, a.firstname, a.secondname,a.admin_level, a.surname, a.email, a.username, a.phone_number, a.role_id, r.role_name FROM admin a JOIN roles r ON a.role_id = r.role_id WHERE a.admin_level != 'super_admin'"); // Exclude super_admins from the list
        $stmt->execute();
        $admin_members = $stmt->fetchAll();
        
        foreach ($admin_members as $admin) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($admin['firstname']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['secondname']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['surname']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['role_name']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['phone_number']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['username']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['admin_level']) . "</td>";
            // echo "<td>
            //         <a href='admin_edit.php?id=" . htmlspecialchars($admin['admin_id']) . "'>Edit</a>
            //       </td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
