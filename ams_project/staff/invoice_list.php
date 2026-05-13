<?php
include __DIR__ . '/../header.php';
require_once __DIR__ . '/../config.php';

session_start();

// 🔐 Proper authentication check (safe + complete)
if (
    !isset($_SESSION['user_id']) ||
    empty($_SESSION['user_id']) ||
    !isset($_SESSION['login_type']) ||
    $_SESSION['login_type'] !== 'staff'
) {
    $_SESSION['error'] = "Please log in as staff to access this page.";
    header('Location: ../staff/staff_login.php');
    exit();
}

// 📦 Fetch payments safely
$stmt = $conn->prepare("
    SELECT * 
    FROM payment 
    ORDER BY payment_date DESC
");

$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<head>
    <title>Payment List</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>

<div class="outer_container">

    <h2>Payments List</h2>

    <div class="inner_container">

        <table border="1" cellpadding="10">

            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Bid ID</th>
                    <th>Bidder ID</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Transaction Reference</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($payments)): ?>

                <?php foreach ($payments as $payment): ?>

                    <?php
                        $statusClass = "status-" . htmlspecialchars($payment['payment_status']);
                    ?>

                    <tr>
                        <td><?= htmlspecialchars($payment['payment_id']) ?></td>
                        <td><?= htmlspecialchars($payment['bid_id']) ?></td>
                        <td><?= htmlspecialchars($payment['bidder_id']) ?></td>
                        <td><?= htmlspecialchars($payment['payment_method']) ?></td>
                        <td><?= htmlspecialchars($payment['amount']) ?></td>
                        <td><?= htmlspecialchars($payment['payment_date']) ?></td>
                        <td><?= htmlspecialchars($payment['transaction_reference']) ?></td>

                        <td class="<?= $statusClass ?>">
                            <?= htmlspecialchars($payment['payment_status']) ?>
                        </td>

                        <td>
                            <a href="update_invoice.php?payment_id=<?= urlencode($payment['payment_id']) ?>">
                                View
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9">No payments found.</td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

<?php include __DIR__ . '/../footer.php'; ?>