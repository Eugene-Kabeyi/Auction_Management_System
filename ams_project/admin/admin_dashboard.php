<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
?>

<main class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <p class="dashboard-subtitle">System overview and management</p>
    </div>

    <!-- Admin Stats -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value">128</div>
            <div class="stat-label">Items Listed</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🔨</div>
            <div class="stat-value">12</div>
            <div class="stat-label">Active Auctions</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value">342</div>
            <div class="stat-label">Registered Users</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value">$98,430</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="quick-actions">
        <h2 class="section-title">Admin Actions</h2>
        <div class="actions-grid">

            <div class="action-btn">
                <div class="action-icon">➕</div>
                <div class="action-label">Create Auction</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">📦</div>
                <div class="action-label">Approve Consignments</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">👥</div>
                <div class="action-label">Manage Users</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">📊</div>
                <div class="action-label">View Reports</div>
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
                <div class="action-icon">🧾</div>
                <div class="action-label">Settlement Processing</div>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>
