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
$stmt = $conn->prepare("SELECT * FROM activity_logs ORDER BY created_at DESC");
$stmt->execute();
$logs = $stmt->fetchAll();
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
                    <td><?= htmlspecialchars($log['log_id']) ?></td>
                    <td><?= htmlspecialchars($log['user_id']) ?></td>
                    <td><?= htmlspecialchars($log['username']) ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
<?php include __DIR__ . ("/../footer.php")?>