<?php include 'header.php';
include 'config.php';
?>
<!-- dashboard.php -->
<main class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Overview</h1>
        <p class="dashboard-subtitle">Welcome to your Auctioneer Management System dashboard</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon icon-auction">📈</div>
            <div class="stat-value" id="activeAuctions">12</div>
            <div class="stat-label">Active Auctions</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-bid">💰</div>
            <div class="stat-value" id="totalBids">1,247</div>
            <div class="stat-label">Total Bids Today</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-revenue">💵</div>
            <div class="stat-value">$89,450</div>
            <div class="stat-label">Revenue (MTD)</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-users">👥</div>
            <div class="stat-value" id="activeBidders">348</div>
            <div class="stat-label">Active Bidders</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2 class="section-title">Quick Actions</h2>
        <div class="actions-grid">
            <div class="action-btn" ">
                <div class="action-icon">➕</div>
                <div class="action-label">Create Auction</div>
            </div>
            
            <div class="action-btn" ">
                <div class="action-icon">📦</div>
                <div class="action-label">Manage Inventory</div>
            </div>
            
            <div class="action-btn" ">
                <div class="action-icon">📊</div>
                <div class="action-label">View Reports</div>
            </div>
            
            <div class="action-btn" ">
                <div class="action-icon">🎯</div>
                <div class="action-label">Add New Item</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="quick-actions">
        <h2 class="section-title">Recent Activity</h2>
        <div class="actions-grid">
            <div class="action-btn">
                <div class="action-icon">⏰</div>
                <div class="action-label">Live Auctions: <span id="liveAuctions">3</span></div>
            </div>
            
            <div class="action-btn">
                <div class="action-icon">📋</div>
                <div class="action-label">Pending Items: <span id="pendingItems">24</span></div>
            </div>
            
            <div class="action-btn">
                <div class="action-icon">🔔</div>
                <div class="action-label">Notifications: <span id="notificationCount">5</span></div>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>