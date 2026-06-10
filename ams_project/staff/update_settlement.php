<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    $_SESSION['error'] = "Please log in as staff.";
    header("Location: ../staff/staff_login.php");
    exit();
}

if (!isset($_GET['settlement_id']) || empty($_GET['settlement_id'])) {
    $_SESSION['error'] = "Invalid settlement.";
    header("Location: settlement_list.php");
    exit();
}

$settlement_id = intval($_GET['settlement_id']);

$stmt = mysqli_prepare($conn, "
    SELECT *
    FROM settlement
    WHERE settlement_id = ?
");

mysqli_stmt_bind_param($stmt, "i", $settlement_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$settlement = mysqli_fetch_assoc($result);

if (!$settlement) {
    $_SESSION['error'] = "Settlement not found.";
    header("Location: settlement_list.php");
    exit();
}
?>

<head>
    <title>Update Settlement</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>

    <div class="outer_container f_container">

        <h2>Update Settlement</h2>

        <form method="POST" action="update_settlement_handler.php" onsubmit="return validateSettlement()">
            <input type="hidden" name="settlement_id" value="<?= $settlement['settlement_id']; ?>">

            <label>Payment ID</label>
            <input type="text" id="payment_id" value="<?=  ($settlement['payment_id']); ?>">

            <label>Auction Item ID</label>
            <input type="text" id="auction_item_id" value="<?=  ($settlement['auction_item_id']); ?>">

            <label>Amount Due</label>
            <input type="text" id="amount_due" value="<?=  ($settlement['amount_due']); ?>">

            <label>Commission Rate (%)</label>
            <input type="text" id="commission_rate" value="<?=  ($settlement['commission_rate']); ?>">

            <label>Commission Amount</label>
            <input type="text" id="commission_amount"
                value="<?=  ($settlement['commission_amount']); ?>">

            <label>Net Amount</label>
            <input type="text" id="net_amount" value="<?=  ($settlement['net_amount']); ?>">

            <label>Settlement Date</label>
            <input type="date" id="settlement_date" name="settlement_date"
                value="<?= $settlement['settlement_date']; ?>">

            <label>Payment Method</label>
            <select id="payment_method" name="payment_method">

                <option value="bank_transfer" <?= ($settlement['payment_method'] == "bank_transfer") ? "selected" : "" ?>>
                    Bank Transfer </option>

                <option value="cheque" <?= ($settlement['payment_method'] == "cheque") ? "selected" : "" ?>> Cheque
                </option>

                <option value="mobile_money" <?= ($settlement['payment_method'] == "mobile_money") ? "selected" : "" ?>>
                    Mobile Money </option>

            </select>

            <label>Transaction Reference</label>
            <input type="text" id="transaction_reference" name="transaction_reference"
                value="<?=  ($settlement['transaction_reference']); ?>">

            <label>Status</label>
            <select id="status" name="status">

                <option value="pending" <?= ($settlement['status'] == "pending") ? "selected" : "" ?>> Pending </option>

                <option value="processing" <?= ($settlement['status'] == "processing") ? "selected" : "" ?>> Processing
                </option>
                <option value="completed" <?= ($settlement['status'] == "completed") ? "selected" : "" ?>> Completed
                </option>
                <option value="cancelled" <?= ($settlement['status'] == "cancelled") ? "selected" : "" ?>> Cancelled
                </option>

            </select>

            <button type="submit"> Update Settlement </button>

        </form>

    </div>

    <script>

        // readonly fields
        document.getElementById("payment_id").readOnly = true;
        document.getElementById("auction_item_id").readOnly = true;
        document.getElementById("amount_due").readOnly = true;
        document.getElementById("commission_rate").readOnly = true;
        document.getElementById("commission_amount").readOnly = true;
        document.getElementById("net_amount").readOnly = true;

        function validateSettlement() {

            if (document.getElementById("settlement_date").value == "") {
                alert("Settlement date required");
                return false;
            }

            if (document.getElementById("payment_method").value == "") {
                alert("Select payment method");
                return false;
            }

            return true;
        }

    </script>

</body>

<?php include __DIR__ . '/../footer.php'; ?>