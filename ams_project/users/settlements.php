<?php
include __DIR__ . ('/../header.php');

if (empty($_SESSION['user_id']) || $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please Login as Seller to access the page";
    header("Location: login.php");
    exit();
}

include __DIR__ . ('/../config.php');

$stmt = $conn->prepare("
    SELECT 
        s.settlement_id,
        s.auction_item_id,
        s.amount_due,
        s.commission_rate,
        s.commission_amount,
        s.net_amount,
        s.settlement_date,
        s.status,
        s.payment_method,
        s.transaction_reference,
        s.created_at,
        s.updated_at,
        a.item_name AS item_title
    FROM settlement s
    JOIN consigner_items a ON s.auction_item_id = a.item_id
    WHERE a.consigner_id = :seller_id
    ORDER BY s.created_at DESC
");

$stmt->execute([
    ':seller_id' => $_SESSION['user_id']
]);

$settlements = $stmt->fetchAll();
?>

<head>
    <style>

                        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #ebe9e9;
        }

        .outer_container {
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: center;
            margin: 20px auto;

        }

        h2 {
            text-align: center;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .status-pending { color: orange; font-weight: bold; }
        .status-processing { color: blue; font-weight: bold; }
        .status-completed { color: green; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
        .back {
            display: inline-block;
            margin: 10px 0;
            padding: 8px 16px;
            background-color: #1f2933;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .back:hover {
            background-color: #111827;
            color: white;
        }
    </style>
</head>

<body>
    <h2>My Settlements</h2>
    <!-- back button to user_dashboard.php -->
    <a href="user_dashboard.php" class="back">Back to Dashboard</a>

    <div class="outer_container">
        <table>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Amount Due</th>
                <th>Commission (%)</th>
                <th>Commission Amount</th>
                <th>Net Amount</th>
                <th>Status</th>
                <th>Payment Method</th>
                <th>Transaction Ref</th>
                <th>Settlement Date</th>
            </tr>

            <?php 
            if (empty($settlements)) {
                echo "<tr><td colspan='10'>No settlements found.</td></tr>";
            }

            foreach ($settlements as $settlement): 
                $statusClass = "status-" . htmlspecialchars($settlement['status']);
            ?>
                <tr>
                    <td><?= htmlspecialchars($settlement['settlement_id']) ?></td>
                    <td><?= htmlspecialchars($settlement['item_title']) ?></td>
                    <td>$<?= htmlspecialchars($settlement['amount_due']) ?></td>
                    <td><?= htmlspecialchars($settlement['commission_rate']) ?>%</td>
                    <td>$<?= htmlspecialchars($settlement['commission_amount']) ?></td>
                    <td><strong>$<?= htmlspecialchars($settlement['net_amount']) ?></strong></td>
                    <td class="<?= $statusClass ?>">
                        <?= htmlspecialchars($settlement['status']) ?>
                    </td>
                    <td><?= htmlspecialchars($settlement['payment_method'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($settlement['transaction_reference'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($settlement['settlement_date'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

<?php
include __DIR__ . ("/../footer.php");
?>