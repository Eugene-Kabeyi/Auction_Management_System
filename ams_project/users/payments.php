<?php
// This file lets a user start a new payment

include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please log in first.";
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all auctions where this user won but hasn't paid yet
$stmt = mysqli_prepare($conn, "
        SELECT 
        ab.bid_id,
        ab.auction_id,
        ab.amount_bidded,
        a.auction_name,
        i.invoice_id,
        i.invoice_number,
        i.tax_amount,
        i.total_amount,
        i.status AS invoice_status

    FROM auction_bids ab

    JOIN auctions a
        ON ab.auction_id = a.auction_id

    JOIN invoices i
        ON ab.bid_id = i.bid_id

    WHERE ab.bidder_id = ?
    AND ab.result = 'won'

    AND i.status IN ('unpaid', 'overdue')

    AND NOT EXISTS (
        SELECT 1
        FROM payment p
        WHERE p.invoice_id = i.invoice_id
        AND p.payment_status IN ('pending', 'completed')
    )
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$winning_bids = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

?>



<head>
    <title>Start Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .container {

            margin: 20px 200px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            flex-direction: column;


        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[readonly] {
            background: #f8f9fa;
        }

        .btn {
            background: #1f2933;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .btn:hover {
            background: #2c3e50;
        }

        .back-btn {
            display: inline-block;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }

        .back-btn:hover {
            color: #333;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .info-box {
            background: #e7f3ff;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            font-size: 14px;
            color: #004085;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Initiate a New Payment</h2>

        <!-- Show error message if any -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Check if user has any unpaid winning bids -->
        <?php if (empty($winning_bids)): ?>
            <div class="alert alert-info">
                You don't have any winning bids that need payment.
            </div>
            <a href="payment_history.php" class="back-btn">← Back to My Payments</a>
        <?php else: ?>

            <!-- Payment Form -->
            <form method="POST" action="payments_handler.php" onsubmit="return validateForm()">

                <!-- Select which auction to pay for -->
                <select name="bid_id" id="bid_id" required onchange="updateAmount()">

                    <option value="">-- Choose an auction --</option>

                    <?php foreach ($winning_bids as $bid): ?>
                        <option value="<?= $bid['bid_id']; ?>" data-amount="<?= $bid['amount_bidded']; ?>"
                            data-tax="<?= $bid['tax_amount'] ?? 0; ?>" data-total="<?= $bid['total_amount'] ?? 0; ?>"
                            data-invoice="<?= $bid['invoice_id']; ?>">

                            <?= $bid['auction_name']; ?> -
                            Won: Ksh <?= number_format($bid['amount_bidded'], 2); ?>
                        </option>
                    <?php endforeach; ?>

                </select>

                <!-- Show selected bid info -->
                <div id="bidInfo" class="info-box" style="display: none;"></div>

                <!-- Amount to pay (auto-filled, cannot change) -->
                <div class="form-group">
                    <label for="invoice_id">Invoice ID:</label>
                    <input type="text" id="invoice_id" name="invoice_id">
                    <label>Amount (Ksh):</label>
                    <input type="text" id="amount" name="amount" placeholder="Select an auction to see amount" readonly>
                    <small style="color: #666;">Amount is based on your winning bid</small>
                    <label>Tax Amount (Ksh):</label>
                    <input type="text" id="tax_amount" name="tax_amount" readonly>

                    <label>Total Amount to pay(Ksh):</label>
                    <input type="text" id="total_amount" name="total_amount" readonly>
                </div>

                <!-- Payment method selection -->
                <div class="form-group">
                    <label>How would you like to pay?</label>
                    <select name="payment_method" required>
                        <option value="">-- Select payment method --</option>
                        <option value="credit_card">💳 Credit Card</option>
                        <option value="bank_transfer">🏦 Bank Transfer</option>
                        <option value="mobile_money">📱 Mobile Money</option>
                        <option value="cash">💰 Cash (Pay in person)</option>
                    </select>
                </div>

                <!-- Transaction reference (optional) -->
                <div class="form-group">
                    <label>Transaction Reference (if you have one):</label>
                    <input type="text" name="transaction_reference" placeholder="e.g., MTC123456">
                    <small style="color: #666;">Leave empty if you don't have it yet</small>
                </div>

                <!-- Submit button -->
                <button type="submit" name="initiate_payment" class="btn">Start Payment</button>
            </form>

            <a href="payment_history.php" class="back-btn">← Back to My Payments</a>
        <?php endif; ?>
    </div>

    <script>
        //make amount readonly
        document.getElementById('amount').setAttribute('readonly', true);
        document.getElementById('invoice_id').setAttribute('readonly', true);



        // This function runs when user selects an auction
        function updateAmount() {
            const select = document.getElementById('bid_id');
            const amountInput = document.getElementById('amount');
            const taxInput = document.getElementById('tax_amount');
            const totalInput = document.getElementById('total_amount');
            const invoiceInput = document.getElementById('invoice_id');

            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                amountInput.value = selectedOption.dataset.amount;
                taxInput.value = selectedOption.dataset.tax;
                totalInput.value = selectedOption.dataset.total;
                invoiceInput.value = selectedOption.dataset.invoice;

            } else {
                amountInput.value = '';
                taxInput.value = '';
                totalInput.value = '';
                invoiceInput.value = '';
            }
        }

        // This function runs when form is submitted
        function validateForm() {
            const bidSelect = document.getElementById('bid_id');
            const amount = document.getElementById('amount').value;


            if (!bidSelect.value) {
                alert('Please select an auction to pay for');
                return false;
            }

            if (amount <= 0) {
                alert('Invalid amount');
                return false;
            }
            if (isNaN(amount)) {
                alert('Amount must be a number');
                return false;
            }

            return true;
        }
    </script>
</body>

<?php include __DIR__ . '/../footer.php'; ?>