<?php
session_start();
include __DIR__ . '/../header.php';

include __DIR__. '/../config.php';

$stmt = $conn->prepare("SELECT COUNT(*) FROM consigner_items WHERE consigner_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$items_consigned = $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT COUNT(DISTINCT auction_id) FROM auction_bids WHERE bidder_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$auctions_participated = $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT COUNT(*) FROM auction_bids WHERE bidder_id = ? AND result = 'won'");
$stmt->execute([$_SESSION['user_id']]);
$bids_won = $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT IFNULL(SUM(amount),0) FROM payment WHERE bidder_id = ? AND payment_status='completed'");
$stmt->execute([$_SESSION['user_id']]);
$total_payments = $stmt->fetchColumn();
?>

<head>
    
</head>

<main class="dashboard-container">

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?= htmlspecialchars($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    <div class="dashboard-header">
        <h1 class="dashboard-title">My Dashboard</h1>
        <p class="dashboard-subtitle">Manage your consignments, bids, and auctions</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon icon-auction">📦</div>
            <div class="stat-value"><?= htmlspecialchars($items_consigned) ?? '0'?></div>
            <div class="stat-label">Items Consigned</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-bid">🔨</div>
            <div class="stat-value"><?=htmlspecialchars($auctions_participated )?? '0'?></div>
            <div class="stat-label">Auctions Participated</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-revenue">🏆</div>
            <div class="stat-value"><?= htmlspecialchars($bids_won) ?? '0'?></div>
            <div class="stat-label">Bids Won</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-users">💰</div>
            <div class="stat-value">Ksh &nbsp;<?= number_format($total_payments,2) ?? '0'?></div>
            <div class="stat-label">Payment History</div>
        </div>
    </div>

    <!-- User Actions -->
    <div class="quick-actions">
        <h2 class="section-title">My Actions</h2>
        <div class="actions-grid">

            <div class="action-btn"><a href="consign_item.php">
                    <div class="action-icon">➕</div>
                    <div class="action-label">Add Item for Consignment</div>
                </a>
            </div>

            <div class="action-btn"><a href="auction_list.php">
                    <div class="action-icon">🔨</div>
                    <div class="action-label">Participate in Auctions</div>
                </a>
            </div>

            <div class="action-btn"><a href="payments.php">
                    <div class="action-icon">💳</div>
                    <div class="action-label">Pay for Bid Won</div>
                </a>
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