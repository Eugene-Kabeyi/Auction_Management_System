<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../log_activity.php';

if (isset($_SESSION['user_id']) && $_SESSION['login_type'] === 'admin') {
    // User is logged in and has the admin role, allow access to the page
} else {
    // User is not logged in or does not have the admin role, redirect to login page
    header("Location: ../staff/staff_login.php");
    logActivity($conn, $_SESSION['user_id'] ?? null, $_SESSION['username'] ?? 'Unknown', "Failed to access admin dashboard without admin privileges.");
    session_destroy();  
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}

include __DIR__ . '/../config.php';
// Fetch total number of users for dashboard stats
$stmt = mysqli_prepare($conn, 'SELECT COUNT(*) FROM users');
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$count_users = mysqli_fetch_array($results)[0];

// Fetch total number of items for dashboard stats
$stmt = mysqli_prepare($conn, 'SELECT COUNT(*) FROM evaluated_items WHERE final_decision = "Approved"');
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$count_items = mysqli_fetch_array($results)[0]; 

// Fetch live auctions for dashboard display 
$stmt = mysqli_prepare($conn, 'SELECT * FROM auctions WHERE status = "upcoming" OR status = "ongoing"');
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$live_auctions = mysqli_fetch_all($results, MYSQLI_ASSOC);  

// Fetch payments processed
$stmt = mysqli_prepare($conn, "SELECT SUM(amount) FROM payment WHERE payment_status =  'completed' ");
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$payments = mysqli_fetch_array($results)[0];


?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?> 
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
<main class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <p class="dashboard-subtitle">System overview and management</p>
    </div>

    <!-- Admin Stats -->
    <div class="stats-container">
        <div class="stat-card">
            <a href="../staff/evaluated_list.php" style="text-decoration:none;">
            <div class="stat-icon">📦</div>
            <div class="stat-value"><?= $count_items ?></div>
            <div class="stat-label">Items Listed</div></a>
        </div>
        <div class="stat-card">
            <a href="../users/auction_list.php" style="text-decoration:none;">
            <div class="stat-icon">🔨</div>
            <div class="stat-value"><?= count($live_auctions) ?></div>
            <div class="stat-label">Active Auctions</div>
            </a>
        </div>

        <div class="stat-card"><a href="user_list.php" style="text-decoration:none;">
            <div class="stat-icon">👥</div>
            <div class="stat-value"><?= $count_users ?> </div>
            <div class="stat-label">Registered Users</div></a>
        </div>

        <div class="stat-card">
            <a href="../staff/payment_list.php" style="text-decoration:none;">
            <div class="stat-icon">💰</div>
            <div class="stat-value">Ksh <?= number_format($payments, 2) ?></div>
            <div class="stat-label">Total Revenue</div>
            </a>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="quick-actions">
        <h2 class="section-title">Admin Actions</h2>
        <div class="actions-grid">

            <div class="action-btn"><a href="admin_list.php">
                <div class="action-icon">➕</div>
                <div class="action-label">Admins</div></a>
            </div>

            <div class="action-btn"><a href="role.php">
                <div class="action-icon">📦</div>
                <div class="action-label">Roles</div></a>
            </div>

            <div class="action-btn"><a href="staff_list.php">
                <div class="action-icon">👥</div>
                <div class="action-label">Manage Staff</div>
                </a>
            </div>

            <div class="action-btn"><a href="department_list.php">
                <div class="action-icon">📊</div>
                <div class="action-label">Departments</div></a>
            </div>

        </div>
    </div>

    <!-- Admin Monitoring -->
    <div class="quick-actions">
        <h2 class="section-title">System Monitoring</h2>
        <div class="actions-grid">

            <div class="action-btn">
                <div class="action-icon">⏳</div>
                <div class="action-label">Pending Approvals</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">🔔</div>
                <div class="action-label">System Notifications</div>
            </div>

            <div class="action-btn">
                <a href="system_logs.php">
                    <div class="action-icon">🧾</div>
                    <div class="action-label">System Logs</div>
                </a>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>
