<?php
include __DIR__  .('/../header.php');
if(empty($_SESSION['login_type']) || $_SESSION['login_type'] !== 'admin'){

header('Location: ../staff/staff_login.php ');
session_destroy();
exit();
}

include __DIR__ . ('/../config.php');

$stmt = mysqli_prepare($conn, "SELECT firstname, secondname ,surname ,username FROM users");
mysqli_stmt_execute($stmt);
$users = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

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

       
    </style>
</head>
<body>
    <div class="outer_container">
        <h2>Users List</h2>
        <table>
            <tr>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Surname</th>
                <th>Username</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?=  ($user['firstname'])?></td>
                <td><?=  ($user['secondname'])?></td>
                <td><?=  ($user['surname'])?></td>
                <td><?=  ($user['username'])?></td>

            </tr>
            <?php endforeach ?>

        </table>
    </div>
    
</body>
<?php
include __DIR__ . ('/../footer.php');
?>