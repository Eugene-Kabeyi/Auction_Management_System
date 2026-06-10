<?php
include __DIR__ . ('/../header.php');

if (empty($_SESSION['user_id']) && $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please Login to access the page";
    header("Location: login.php");
    exit();
}

include __DIR__ . ('/../config.php');

$stmt = mysqli_prepare($conn, "
    SELECT 
        p.payment_id,
        p.bid_id,
        p.amount,
        p.payment_method,
        p.payment_status,
        p.transaction_reference,
        p.payment_date,
        p.completed_at,
        a.auction_id,
        auc.auction_name,
        auc.auction_code
    FROM payment p
    LEFT JOIN auction_bids a ON p.bid_id = a.bid_id
    LEFT JOIN auctions auc ON a.auction_id = auc.auction_id
    WHERE p.bidder_id = ?
    ORDER BY p.payment_date DESC
");

mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);

$payments = mysqli_stmt_get_result($stmt);
?>

<head>
    <title>Payment History</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
    <style>
        .back {
            display: inline-block;
           
            text-decoration:none;
            padding: 10px 15px;
            background-color: #2c2d2d;
            color: #fff;
            border-radius: 4px;
            margin: auto;
        }
        .back:hover {
            background-color: #ffffff;
            color: #2c2d2d;
        }
    </style>
</head>

<body>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash success">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <h2>My Payments</h2>
    <!-- back button to user_dashboard.php -->
    <a href="user_dashboard.php" class="back">Back to Dashboard</a></div>
    <div class="outer_container">
        <table>
            <tr>
                <th>Payment ID</th>
                <th>Auction</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Transaction Ref</th>
                <th>Payment Date</th>
                <th>Completed At</th>
            </tr>

            <?php 
            if (empty($payments)) {
                echo "<tr><td colspan='8'>No payments found.</td></tr>";
            }

            foreach ($payments as $payment): 
                $statusClass = "status-" . htmlspecialchars($payment['payment_status']);
            ?>
                <tr>
                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                    <td>
                        <?= htmlspecialchars($payment['auction_name'] ?? 'N/A') ?>
                        (<?= htmlspecialchars($payment['auction_code'] ?? '-') ?>)
                    </td>
                    <td>$<?= htmlspecialchars($payment['amount']) ?></td>
                    <td><?= htmlspecialchars($payment['payment_method']) ?></td>
                    <td class="<?= $statusClass ?>">
                        <?= htmlspecialchars($payment['payment_status']) ?>
                    </td>
                    <td><?= htmlspecialchars($payment['transaction_reference'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                    <td><?= htmlspecialchars($payment['completed_at'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

<?php
include __DIR__ . ("/../footer.php");
?>