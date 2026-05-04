<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
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
        <h2>Add Settlement</h2>
        <!-- <a href="settlement_list.php" class="back">Back to Settlement List</a> -->

        <form action="" method="POST" onsubmit="return validateSettlement()">

            <label>Auction Item ID:</label>
            <input type="text" name="auction_item_id" id="item_id" required>

            <label>Amount Due:</label>
            <input type="text" name="amount_due" id="amount" required>

            <label>Commission Rate (%):</label>
            <input type="text" name="commision_rate" id="commision_rate" required>

            <label>Settlement Date:</label>
            <input type="text" name="settlement_date" id="settlement_date" required>

            <label>Payment Method:</label>
            <select name="payment_method" id="payment_method">
                <option value="">-- Select --</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cheque">Cheque</option>
                <option value="mobile_money">Mobile Money</option>
            </select>

            <label>Transaction Reference:</label>
            <input type="text" name="transaction_reference" id="reference">

            <label>Status:</label>
            <select name="status" id="status">
                <option value="pending" selected>Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <button type="submit">Add Settlement</button>
        </form>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>
<script>
    // Main controller
    function validateSettlement() {

        if (!validateAuctionID()) return false;
        if (!validateAmount()) return false;
        if (!validateCommission()) return false;
        if (!validateDate()) return false;
        if (!validatePaymentMethod()) return false;
        if (!validateReference()) return false;

        return true;
    }

    // Auction Item ID
    function validateAuctionID() {
        var id = document.getElementById("item_id").value;

        if (id.length == 0 || isNaN(id)) {
            alert("Auction Item ID must be a valid number");
            return false;
        }

        if (id <= 0) {
            alert("Auction Item ID must be greater than 0");
            return false;
        }

        return true;
    }

    // Amount Due
    function validateAmount() {
        var amount = document.getElementById("amount").value;

        if (amount.length == 0 || isNaN(amount)) {
            alert("Amount must be a valid number");
            document.getElementById("amount").focus();
            return false;
        }

        if (parseFloat(amount) <= 0) {
            alert("Amount must be greater than 0");
            document.getElementById("amount").focus();
            return false;
        }

        return true;
    }

    // Commission Rate
    function validateCommission() {
        var rate = document.getElementById("commision_rate").value;

        if (rate.length == 0 || isNaN(rate)) {
            alert("Commission rate must be a number");
            document.getElementById("commision_rate").focus();
            return false;
        }

        if (rate < 0 || rate > 100) {
            alert("Commission rate must be between 0 and 100%");
            document.getElementById("commision_rate").focus();
            return false;
        }

        return true;
    }

    // Settlement Date (dd/mm/yyyy like your template)
    function validateDate() {
        var cdate = document.getElementById("settlement_date").value;

        if (cdate.indexOf("/") == -1) {
            alert("Date must be in format dd/mm/yyyy");
            return false;
        }

        var comps = cdate.split("/");

        if (comps.length < 3 || comps[2].length != 4) {
            alert("Invalid date format (dd/mm/yyyy)");
            return false;
        }

        if (isNaN(comps[0]) || isNaN(comps[1]) || isNaN(comps[2])) {
            alert("Date must contain numbers only");
            return false;
        }

        var day = parseInt(comps[0]);
        var month = parseInt(comps[1]);
        var year = parseInt(comps[2]);

        if (day < 1 || day > 31) {
            alert("Invalid day");
            return false;
        }

        if (month < 1 || month > 12) {
            alert("Invalid month");
            return false;
        }

        // Prevent future dates
        var today = new Date();
        var enteredDate = new Date(year, month - 1, day);

        if (enteredDate > today) {
            alert("Settlement date cannot be in the future");
            return false;
        }

        return true;
    }

    //Payment Method
    function validatePaymentMethod() {
        var index = document.getElementById("payment_method").selectedIndex;

        if (index == 0) {
            alert("Please select a payment method");
            return false;
        }

        return true;
    }

    // Transaction Reference
    function validateReference() {
        var method = document.getElementById("payment_method").value;
        var ref = document.getElementById("reference").value;

        // Only require reference for certain methods
        if (method == "bank_transfer" || method == "mobile_money") {
            if (ref.length == 0) {
                alert("Transaction reference is required for this payment method");
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