<?php include __DIR__ . '/../header.php'; ?>

<main class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">My Dashboard</h1>
        <p class="dashboard-subtitle">Manage your consignments, bids, and auctions</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon icon-auction">📦</div>
            <div class="stat-value">5</div>
            <div class="stat-label">Items Consigned</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-bid">🔨</div>
            <div class="stat-value">18</div>
            <div class="stat-label">Auctions Participated</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-revenue">🏆</div>
            <div class="stat-value">3</div>
            <div class="stat-label">Bids Won</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-users">💰</div>
            <div class="stat-value">$12,450</div>
            <div class="stat-label">Settlement Amount</div>
        </div>
    </div>

    <!-- User Actions -->
    <div class="quick-actions">
        <h2 class="section-title">My Actions</h2>
        <div class="actions-grid">

            <div class="action-btn">
                <div class="action-icon">➕</div>
                <div class="action-label">Add Item for Consignment</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">📝</div>
                <div class="action-label">Register for Auctions</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">🔨</div>
                <div class="action-label">Participate in Auctions</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">💳</div>
                <div class="action-label">Pay for Bid Won</div>
            </div>

        </div>
    </div>

    <!-- History & Settlement -->
    <div class="quick-actions">
        <h2 class="section-title">My Records</h2>
        <div class="actions-grid">

            <div class="action-btn">
                <div class="action-icon">🏆</div>
                <div class="action-label">View Bids Won</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">📜</div>
                <div class="action-label">Bid & Auction History</div>
            </div>

            <div class="action-btn">
                <div class="action-icon">💵</div>
                <div class="action-label">Settlement Amounts</div>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>
