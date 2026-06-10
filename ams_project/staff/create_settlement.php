<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
$payment_id = $_GET['payment_id'] ;
$stmt = mysqli_prepare($conn, "
    SELECT * FROM payment    
    WHERE payment_id = ?
");
mysqli_stmt_bind_param($stmt, "i", $payment_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$record = mysqli_fetch_assoc($result);

if (!$record) {
    $_SESSION['error'] = "Payment record not found.";
    header('Location: payment_list.php');
    exit();
}
// Ensure payment_id is not in settlements to prevent duplicates
$check_stmt = mysqli_prepare($conn, "
    SELECT * FROM settlement
    WHERE payment_id = ?
");
mysqli_stmt_bind_param($check_stmt, "i", $payment_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);
if (mysqli_num_rows($check_result) > 0) {
    $_SESSION['error'] = "A settlement for this payment already exists. You are forwarded to the update page.";
    header('Location: update_settlement.php?settlement_id=' . mysqli_fetch_assoc($check_result)['settlement_id']);
    exit();
}
// find item_id from auctions using bid_id from payment
$stmt = mysqli_prepare($conn, "
    SELECT
        p.payment_id,
        p.amount,
        p.payment_status,
        ab.bid_id,
        a.auction_id,
        a.item_id
    FROM payment p
    JOIN auction_bids ab
        ON p.bid_id = ab.bid_id
    JOIN auctions a
        ON ab.auction_id = a.auction_id
    WHERE p.payment_id = ?
");
mysqli_stmt_bind_param($stmt, "i", $payment_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item_record = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM auction_settings";
$result = mysqli_query($conn, $sql);
$settings = mysqli_fetch_assoc($result);

$commission_rate = $settings['commission_rate'];

//calculations
$amount = $record['amount'];
$commission_amount = ($commission_rate / 100) * $amount;
$net_amount = $amount - $commission_amount;

?>

<head>
    <title>Create Settlement</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>
    <div class="outer_container f_container"> ">
        <!-- error messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; ?></div>
        <?php endif; ?>

    <h2>Create Settlement</h2>

    <form method="POST" action="create_settlement_handler.php" onsubmit="return validateSettlement()">

        <label>Payment ID:</label>
        <input type="text" id="payment_id" name="payment_id" value="<?= htmlspecialchars($record['payment_id']) ?>" >

        <label>Auction Item ID:</label>
        <input type="text" id="auction_item_id" name="auction_item_id" value="<?= htmlspecialchars($item_record['item_id']) ?>" >

        <label>Amount Due (Ksh):</label>
        <input type="text" id="amount_due" name="amount_due" value="<?= htmlspecialchars(number_format($record['amount'], 2, '.', '')) ?>" >

        <label>Commission Rate (%):</label>
        <input type="text" id="commission_rate" name="commission_rate" value="<?= htmlspecialchars($settings['commission_rate']) ?>">

        <label>Commission Amount:</label>
        <input type="text" id="commission_amount" name="commission_amount" value="<?= htmlspecialchars(number_format($commission_amount, 2, '.', '')) ?>">

        <label>Net Amount:</label>
        <input type="text" id="net_amount" name="net_amount" value="<?= htmlspecialchars(number_format($net_amount, 2, '.', '')) ?>">

        <label>Settlement Date:</label>
        <input type="text"
               id="settlement_date"
               name="settlement_date"
               placeholder="dd/mm/yyyy">

        <label>Payment Method:</label>
        <select id="payment_method" name="payment_method">
            <option value="">-- Select Method --</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cheque">Cheque</option>
            <option value="mobile_money">Mobile Money</option>
        </select>

        <label>Transaction Reference:</label>
        <input type="text"
               id="transaction_reference"
               name="transaction_reference">

        <label>Status:</label>
        <select id="status" name="status">
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <button type="submit">Create Settlement</button>

    </form>

</div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>
<script>
    // making prefielded fields readonly
    document.getElementById("payment_id").readOnly = true;
    document.getElementById("auction_item_id").readOnly = true;
    document.getElementById("amount_due").readOnly = true;
    document.getElementById("commission_rate").readOnly = true;
    document.getElementById("commission_amount").readOnly = true;
    document.getElementById("net_amount").readOnly = true;



// MAIN VALIDATION CONTROLLER

function validateSettlement() {

    if (!validateAuctionID()) return false;
    if (!validateAmount()) return false;
    if (!validateCommission()) return false;
    if (!validateDate()) return false;
    if (!validatePaymentMethod()) return false;
    if (!validateReference()) return false;

    return true;
}



// AUCTION ITEM ID VALIDATION

function validateAuctionID() {

    var id =
        document.getElementById("auction_item_id").value.trim();

    if (id.length == 0) {
        alert("Auction Item ID is required");
        return false;
    }

    if (isNaN(id)) {
        alert("Auction Item ID must be numeric");
        return false;
    }

    if (parseInt(id) <= 0) {
        alert("Auction Item ID must be greater than 0");
        return false;
    }

    return true;
}


// AMOUNT VALIDATION

function validateAmount() {

    var amount =
        document.getElementById("amount_due").value.trim();

    if (amount.length == 0) {
        alert("Amount Due is required");
        document.getElementById("amount_due").focus();
        return false;
    }

    if (isNaN(amount)) {
        alert("Amount Due must be numeric");
        document.getElementById("amount_due").focus();
        return false;
    }

    if (parseFloat(amount) <= 0) {
        alert("Amount Due must be greater than 0");
        document.getElementById("amount_due").focus();
        return false;
    }

    return true;
}



// COMMISSION VALIDATION

function validateCommission() {

    var rate =
        document.getElementById("commission_rate").value.trim();

    if (rate.length == 0) {
        alert("Commission Rate is required");
        document.getElementById("commission_rate").focus();
        return false;
    }

    if (isNaN(rate)) {
        alert("Commission Rate must be numeric");
        document.getElementById("commission_rate").focus();
        return false;
    }

    if (parseFloat(rate) < 0 ||
        parseFloat(rate) > 100) {

        alert("Commission Rate must be between 0 and 100");
        document.getElementById("commission_rate").focus();
        return false;
    }

    return true;
}


// DATE VALIDATION
// Format: dd/mm/yyyy

function validateDate() {

    var date =
        document.getElementById("settlement_date").value.trim();

    if (date.length == 0) {
        alert("Settlement Date is required");
        return false;
    }

    if (date.indexOf("/") == -1) {
        alert("Date must be in format dd/mm/yyyy");
        return false;
    }

    var parts = date.split("/");

    if (parts.length != 3) {
        alert("Invalid date format");
        return false;
    }

    if (
        isNaN(parts[0]) ||
        isNaN(parts[1]) ||
        isNaN(parts[2])
    ) {
        alert("Date must contain numbers only");
        return false;
    }

    var day =
        parseInt(parts[0]);

    var month =
        parseInt(parts[1]);

    var year =
        parseInt(parts[2]);

    if (day < 1 || day > 31) {
        alert("Invalid day");
        return false;
    }

    if (month < 1 || month > 12) {
        alert("Invalid month");
        return false;
    }

    return true;
}


// PAYMENT METHOD VALIDATION

function validatePaymentMethod() {

    var method =
        document.getElementById("payment_method").value;

    if (method == "") {
        alert("Please select a payment method");
        return false;
    }

    return true;
}


// REFERENCE VALIDATION

function validateReference() {

    var method =
        document.getElementById("payment_method").value;

    var reference =
        document.getElementById("transaction_reference").value.trim();

    if (
        method == "bank_transfer" ||
        method == "mobile_money"
    ) {

        if (reference.length == 0) {

            alert(
                "Transaction Reference is required for " +
                method.replace("_", " ")
            );

            document.getElementById("transaction_reference").focus();

            return false;
        }
    }

    return true;
}

</script>

