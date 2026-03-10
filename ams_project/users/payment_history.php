<?php
include __DIR__ . ('/../header.php');

if (empty($_SESSION['user_id']) || $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please Login to access the page";
    header("Location: login.php");
    exit();
}

include __DIR__ . ('/../config.php');

$stmt = $conn->prepare("
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
    FROM payments p
    LEFT JOIN auction_bids a ON p.bid_id = a.bid_id
    LEFT JOIN auctions auc ON a.auction_id = auc.auction_id
    WHERE p.bidder_id = :user_id
    ORDER BY p.payment_date DESC
");

$stmt->execute([
    ':user_id' => $_SESSION['user_id']
]);

$payments = $stmt->fetchAll();
?>

<head>
    <style>
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 90%;
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

        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-completed {
            color: green;
            font-weight: bold;
        }

        .status-failed {
            color: red;
            font-weight: bold;
        }

        .status-refunded {
            color: blue;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>My Payments</h2>
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