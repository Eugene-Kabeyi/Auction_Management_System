<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
$stmt = $conn->prepare("
    SELECT 
        ci.item_id,
        ci.item_name,
        ci.consigner_id,
        a.auction_id,
        ab.bid_id,
        ab.bidder_id,
        ab.amount_bidded,
        p.payment_id
    FROM consigner_items ci
    JOIN auctions a ON a.item_id = ci.item_id
    JOIN auction_bids ab ON ab.auction_id = a.auction_id AND ab.result = 'won'
    JOIN payment p ON p.bid_id = ab.bid_id AND p.payment_status = 'completed'
    WHERE ci.item_status = 'sold'
");
$stmt->execute();
$records = $stmt->fetchAll();
?>

<head>
    <style>
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 640px;
            margin: 0 auto;
            justify-content: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form label {
            font-weight: bold;
        }

        form input,
        form select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 10px;
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back {
            border-radius: 5px;
            color: #ffffff;
            text-decoration: none;
            background-color: #1f2933;
            padding: 6px 0 6px 30px;
            width: 30%;
        }
    </style>
</head>

<body>
    <div class="outer_container">

    <h2>Create Settlement</h2>

    <form method="POST" action="create_settlement_handler.php" onsubmit="return validateSettlement()">

        <label>Payment ID:</label>
        <input type="text" id="payment_id" name="payment_id">

        <label>Auction Item ID:</label>
        <input type="text" id="auction_item_id" name="auction_item_id">

        <label>Amount Due (Ksh):</label>
        <input type="text" id="amount_due" name="amount_due">

        <label>Commission Rate (%):</label>
        <input type="text" id="commission_rate" name="commission_rate" value="15">

        <label>Commission Amount:</label>
        <input type="text" id="commission_amount" name="commission_amount" >

        <label>Net Amount:</label>
        <input type="text" id="net_amount" name="net_amount" >

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



// AUTO CALCULATE COMMISSION + NET

function calculateAmounts() {

    var amount =
        parseFloat(document.getElementById("amount").value) || 0;

    var rate =
        parseFloat(document.getElementById("commision_rate").value) || 0;

    var commission =
        (amount * rate) / 100;

    var net =
        amount - commission;

    document.getElementById("commission_amount").value =
        commission.toFixed(2);

    document.getElementById("net_amount").value =
        net.toFixed(2);
}


// Run calculation whenever user types
document.getElementById("amount")
    .addEventListener("keyup", calculateAmounts);

document.getElementById("commision_rate")
    .addEventListener("keyup", calculateAmounts);



// AUCTION ITEM ID VALIDATION

function validateAuctionID() {

    var id =
        document.getElementById("item_id").value.trim();

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
        document.getElementById("amount").value.trim();

    if (amount.length == 0) {
        alert("Amount Due is required");
        document.getElementById("amount").focus();
        return false;
    }

    if (isNaN(amount)) {
        alert("Amount Due must be numeric");
        document.getElementById("amount").focus();
        return false;
    }

    if (parseFloat(amount) <= 0) {
        alert("Amount Due must be greater than 0");
        document.getElementById("amount").focus();
        return false;
    }

    return true;
}



// COMMISSION VALIDATION

function validateCommission() {

    var rate =
        document.getElementById("commision_rate").value.trim();

    if (rate.length == 0) {
        alert("Commission Rate is required");
        document.getElementById("commision_rate").focus();
        return false;
    }

    if (isNaN(rate)) {
        alert("Commission Rate must be numeric");
        document.getElementById("commision_rate").focus();
        return false;
    }

    if (parseFloat(rate) < 0 ||
        parseFloat(rate) > 100) {

        alert("Commission Rate must be between 0 and 100");
        document.getElementById("commision_rate").focus();
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
        document.getElementById("reference").value.trim();

    if (
        method == "bank_transfer" ||
        method == "mobile_money"
    ) {

        if (reference.length == 0) {

            alert(
                "Transaction Reference is required for " +
                method.replace("_", " ")
            );

            document.getElementById("reference").focus();

            return false;
        }
    }

    return true;
}

</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $auction_item_id = $_POST['auction_item_id'];
    $amount_due = $_POST['amount_due'];
    $commission_rate = $_POST['commission_rate'];
    $settlement_date = !empty($_POST['settlement_date']) ? $_POST['settlement_date'] : null;
    $status = $_POST['status'];
    $payment_method = !empty($_POST['payment_method']) ? $_POST['payment_method'] : null;
    $tx_reference = !empty($_POST['transaction_reference']) ? trim($_POST['transaction_reference']) : null;
    $staff_id = $_SESSION['user_id'];

    // Calculations
    $commission_amount = ($commission_rate / 100) * $amount_due;
    $net_amount = $amount_due - $commission_amount;

    $stmt = $conn->prepare(
        "INSERT INTO settlements
        (auction_item_id, amount_due, commission_rate, commission_amount, net_amount,
         settlement_date, status, processed_by_staff, payment_method, transaction_reference)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $success = $stmt->execute([
        $auction_item_id,
        $amount_due,
        $commission_rate,
        $commission_amount,
        $net_amount,
        $settlement_date,
        $status,
        $staff_id,
        $payment_method,
        $tx_reference
    ]);

    if ($success) {
        $_SESSION['success'] = "Settlement added successfully";
        header('Location: settlement_list.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to add settlement";
    }
}
?>