<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    session_destroy();
    exit();
}
// Fetch system logs
$stmt = mysqli_prepare($conn, "SELECT * FROM activity_logs ORDER BY created_at DESC");
mysqli_stmt_execute($stmt);
$logs = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
?>
<head>
    <title>System Logs</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>
<body>
    <h2>System Logs</h2>
    <div class="outer_container">
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Username</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>

            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?=  ($log['log_id']) ?></td>
                    <td><?=  ($log['user_id']) ?></td>
                    <td><?=  ($log['username']) ?></td>
                    <td><?=  ($log['action']) ?></td>
                    <td><?=  ($log['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
<?php include __DIR__ . ("/../footer.php")?>