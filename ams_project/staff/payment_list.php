<?php
include __DIR__ . '/../header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    header('Location: ../staff/staff_login.php');
    session_destroy();
    exit();
}

include __DIR__ . '/../config.php';
?>

<head>
    <title>Payment List</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>

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

<div class="outer_container">
    <h2>Payment List</h2>

    <div class="inner_container">
        <table>
            <tr>
                <th>Payment ID</th>
                <th>Invoice ID</th>
                <th>Bid ID</th>
                <th>Bidder ID</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Transaction Ref</th>
                <th>Payment Date</th>
                <th>Actions</th>
            </tr>

            <?php
            $stmt = $conn->query("
                SELECT *
                FROM payment
                ORDER BY payment_date DESC
            ");

            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($payments as $payment) {

                // Status color
                $statusColor = match ($payment['payment_status']) {
                    'completed' => 'green',
                    'pending' => 'orange',
                    'failed' => 'red',
                    'refunded' => 'blue',
                    default => 'black'
                };

                echo "<tr>";

                echo "<td>" . htmlspecialchars($payment['payment_id']) . "</td>";
                echo "<td>" . htmlspecialchars($payment['invoice_id']) . "</td>";
                echo "<td>" . htmlspecialchars($payment['bid_id']) . "</td>";
                echo "<td>" . htmlspecialchars($payment['bidder_id']) . "</td>";
                echo "<td>" . htmlspecialchars($payment['amount']) . "</td>";
                echo "<td>" . htmlspecialchars($payment['payment_method']) . "</td>";

                echo "<td style='color:$statusColor; font-weight:bold;'>" 
                    . htmlspecialchars($payment['payment_status']) . 
                "</td>";

                echo "<td>" . htmlspecialchars($payment['transaction_reference']) . "</td>";
                echo "<td>" . htmlspecialchars($payment['payment_date']) . "</td>";

                echo "<td>
                        <a href='payment_review.php?id=" . $payment['payment_id'] . "'>Review</a>
                      </td>";

                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>

<?php include __DIR__ . '/../footer.php'; ?>