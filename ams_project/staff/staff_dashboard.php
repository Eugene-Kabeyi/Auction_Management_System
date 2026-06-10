<?php
session_start();
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../staff/staff_login.php');
    exit();
}
// SELECTING THIS WEEKS AUCTIONS
$sql = "SELECT COUNT(*) AS count FROM auctions WHERE YEARWEEK(start_time, 1) = YEARWEEK(CURDATE(), 1)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$weekly_auctions = mysqli_fetch_assoc($result)['count'];

//FETCHING LIVE AUCTIONS FOR TODAY
$sql = "SELECT COUNT(*) AS count FROM auctions WHERE start_time <= NOW() AND end_time >= NOW()";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$live_auctions = mysqli_fetch_assoc($result)['count'];

// REVENUE CALCULATION FOR THE YEAR TO DATE
$sql = "SELECT SUM(amount) AS total_revenue FROM payment WHERE YEAR(completed_at) = YEAR(CURDATE())  AND payment_status = 'completed'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_revenue = mysqli_fetch_assoc($result)['total_revenue'];

//NUMBER OF BIDS IN THE LAST 24 HOURS
$sql = "SELECT COUNT(*) AS count FROM auction_bids WHERE created_at>= NOW() - INTERVAL 1 DAY";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_bids = mysqli_fetch_assoc($result)['count'];

// PENDING ITEMS from consigner_items
$sql = "SELECT COUNT(*) AS count FROM consigner_items WHERE item_status = 'pending'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pending_items = mysqli_fetch_assoc($result)['count'];

//USERS IN THE SYSTEM
$sql = "SELECT COUNT(*) AS count FROM users";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_users = mysqli_fetch_assoc($result)['count'];


?>
<!-- dashboard.php -->
<main class="dashboard-container">
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?=  ($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?=  ($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Overview</h1>
        <p class="dashboard-subtitle">Welcome to your Auctioneer Management System dashboard</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon icon-auction">📈</div>
            <div class="stat-value" id="activeAuctions"><?= $weekly_auctions ?></div>
            <div class="stat-label">This Week's Auctions</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-bid">💰</div>
            <div class="stat-value" id="totalBids">1,247</div>
            <div class="stat-label">Total Bids Today</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-revenue">💵</div>
            <div class="stat-value" id="totalRevenue">Ksh<?= number_format($total_revenue, 2) ?></div>
            <div class="stat-label">Revenue (MTD)</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-users">👥</div>
            <div class="stat-value" id="activeBidders"><?= $total_users ?></div>
            <div class="stat-label">Users</div>
        </div>


    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2 class="section-title">Quick Actions</h2>
        <div class="actions-grid">
            <div class="action-btn"><a href="create_auction.php">
                    <div class="action-icon">➕</div>
                    <div class="action-label">Create Auction</div>
                </a>
            </div>

            <div class="action-btn"><a href="list_items.php">
                    <div class="action-icon">📦</div>
                    <div class="action-label">Manage Inventory</div>
                </a></div>

            <div class="action-btn"><a href="invoice_list.php">
                    <div class="action-icon">📊</div>
                    <div class="action-label">Invoices</div>
                </a>
            </div>

            <div class="action-btn"><a href="create_settlement.php">
                    <div class="action-icon">🎯</div>
                    <div class="action-label">Settlements</div>
                </a>
            </div>
            <!-- Manage auctions via auctionlist -->
            <div class="action-btn"><a href="auction_list.php">
                    <div class="action-icon">📋</div>
                    <div class="action-label">Manage Auctions</div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="quick-actions">
        <h2 class="section-title">Recent Activity</h2>
        <div class="actions-grid">
            <div class="action-btn">
                <div class="action-icon">⏰</div>
                <div class="action-label">Live Auctions: <span id="liveAuctions"><?= $live_auctions ?></span></div>
            </div>

            <div class="action-btn">
                <div class="action-icon">📋</div>
                <div class="action-label">Pending Items: <span id="pendingItems"><?= $pending_items ?></span></div>
            </div>

            <div class="action-btn">
                <div class="action-icon">🔔</div>
                <div class="action-label">New Bids: <span id="newBids"><?= $total_bids ?></span></div>
            </div>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../footer.php'; ?>