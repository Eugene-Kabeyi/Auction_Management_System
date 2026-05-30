<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';

session_start();

//  AUTH CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    $_SESSION['error'] = "Please log in as staff to access this page.";
    header('Location: ../staff/staff_login.php');
    exit();
}

//  GET PAYMENT ID
if (!isset($_GET['payment_id']) || empty($_GET['payment_id'])) {
    $_SESSION['error'] = "Invalid payment selected.";
    header('Location: payment_list.php');
    exit();
}

$payment_id = intval($_GET['payment_id']);

//  FETCH PAYMENT
$stmt = $conn->prepare("SELECT * FROM payment WHERE payment_id = ?");
$stmt->execute([$payment_id]);
$payment = $stmt->fetch();

if (!$payment) {
    $_SESSION['error'] = "Payment not found.";
    header('Location: payment_list.php');
    exit();
}

//  CHECK IF INVOICE EXISTS
$check_stmt = $conn->prepare("SELECT * FROM invoices WHERE payment_id = ?");
$check_stmt->execute([$payment_id]);
$invoice = $check_stmt->fetch();

if (!$invoice) {
    $_SESSION['error'] = "No invoice found to update.";
    header('Location: invoice_list.php');
    exit();
}

//  AUTO CALCULATIONS
$amount = $payment['amount'];
$tax_amount = $amount * 0.16;
$total_amount = $amount + $tax_amount;

$due_date = date("Y-m-d", strtotime("+7 days"));


//  HANDLE UPDATE ONLY


?>

<head>
    <title>Create Invoice</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>
    <div class="outer_container f_container">
        <h2>Add New Invoice</h2>
        <!-- <a href="invoice_list.php" class="back">Back to Invoice List</a> -->

        <form id="invoiceForm" action="update_invoice_handler.php" method="POST" onsubmit="return validateInvoice()">

            <label>Invoice Number:</label>
            <input type="text" id="invoice_number" name="invoice_number" value="<?= htmlspecialchars($invoice_number) ?>">

            <label>Bidder ID:</label>
            <input type="text" id="bidder_id" name="bidder_id" value="<?= htmlspecialchars($payment['bidder_id']) ?>">

            <label>Payment ID:</label>
            <input type="text" id="payment_id" name="payment_id" value="<?= htmlspecialchars($payment['payment_id']) ?>">

            <label>Amount:</label>
            <input type="text" id="amount" name="amount" value="<?= htmlspecialchars(number_format($amount, 2, '.', '')) ?>">

            <label>Tax Amount:</label>
            <input type="text" id="tax_amount" name="tax_amount" value="0.00" value="<?= htmlspecialchars(number_format($tax_amount, 2, '.', '')) ?>">

            <label>Total Amount:</label>
            <input type="text" id="total_amount" name="total_amount" value="<?= htmlspecialchars(number_format($total_amount, 2, '.', '')) ?>">

            <label>Due Date:</label>
            <input type="text" id="due_date" name="due_date" placeholder="dd/mm/yyyy" value="<?= htmlspecialchars($due_date) ?>">

            <label>Status:</label>
            <select id="status" name="status">
                <option value="draft">Draft</option>
                <option value="unpaid" selected>Unpaid</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
                <option value="overdue">Overdue</option>
            </select>

            <button type="submit">Add Invoice</button>

        </form>

    </div>

    <script>
        var invoiceNum = document.getElementById("invoice_number").value;
        var amount = document.getElementById("amount").value;
        var tax = document.getElementById("tax_amount").value;
        var total = document.getElementById("total_amount").value;
        var dueDate = document.getElementById("due_date").value;
        var bidderId = document.getElementById("bidder_id").value;
        var paymentId = document.getElementById("payment_id").value;

        // make them readonly since we are auto-calculating them
        invoiceNum.readOnly = true;
        amount.readOnly = true;
        tax.readOnly = true;
        total.readOnly = true;
        bidderId.readOnly = true;
        paymentId.readOnly = true;

       
        // MAIN VALIDATION CONTROLLER
       
        function validateInvoice() {

            if (!validateInvoiceNumber()) return false;
            if (!validateIDs()) return false;
            if (!validateAmounts()) return false;
            if (!validateTotal()) return false;
            if (!validateDate()) return false;

            return true;
        }

        
        // INVOICE NUMBER VALIDATION
      
        function validateInvoiceNumber() {

            var inv = document.getElementById("invoice_number").value.trim();

            if (inv.length == 0) {
                alert("Invoice number is required");
                document.getElementById("invoice_number").focus();
                return false;
            }

            if (inv.length < 3) {
                alert("Invoice number must be at least 3 characters");
                document.getElementById("invoice_number").focus();
                return false;
            }

            return true;
        }

        
        // BIDDER + PAYMENT ID VALIDATION
     
        function validateIDs() {

            var bidder = document.getElementById("bidder_id").value;
            var payment = document.getElementById("payment_id").value;

            if (bidder.length > 0 && isNaN(bidder)) {
                alert("Bidder ID must be a number");
                document.getElementById("bidder_id").focus();
                return false;
            }

            if (payment.length > 0 && isNaN(payment)) {
                alert("Payment ID must be a number");
                document.getElementById("payment_id").focus();
                return false;
            }

            return true;
        }

     
        // AMOUNT + TAX VALIDATION
        
        function validateAmounts() {

            var amount = document.getElementById("amount").value;
            var tax = document.getElementById("tax_amount").value;

            if (amount.length == 0 || isNaN(amount) || parseFloat(amount) <= 0) {
                alert("Amount must be a valid number greater than 0");
                document.getElementById("amount").focus();
                return false;
            }

            if (tax.length > 0 && isNaN(tax)) {
                alert("Tax amount must be a valid number");
                document.getElementById("tax_amount").focus();
                return false;
            }

            return true;
        }

        
        // TOTAL VALIDATION (LOGIC CHECK)
      
        function validateTotal() {

            var amount = parseFloat(document.getElementById("amount").value) || 0;
            var tax = parseFloat(document.getElementById("tax_amount").value) || 0;
            var total = parseFloat(document.getElementById("total_amount").value);

            if (isNaN(total)) {
                alert("Total amount must be a valid number");
                document.getElementById("total_amount").focus();
                return false;
            }

            if (total !== (amount + tax)) {
                alert("Total must equal Amount + Tax");
                document.getElementById("total_amount").focus();
                return false;
            }

            return true;
        }

       
        // DATE VALIDATION (dd/mm/yyyy)
        function validateDate() {

            var date = document.getElementById("due_date").value;

            if (date.length == 0) {
                alert("Due date is required");
                document.getElementById("due_date").focus();
                return false;
            }

            if (date.indexOf("/") == -1) {
                alert("Date must be in format dd/mm/yyyy");
                document.getElementById("due_date").focus();
                return false;
            }

            var parts = date.split("/");

            if (parts.length != 3) {
                alert("Invalid date format");
                return false;
            }

            if (isNaN(parts[0]) || isNaN(parts[1]) || isNaN(parts[2])) {
                alert("Date must contain only numbers");
                return false;
            }

            var day = parseInt(parts[0]);
            var month = parseInt(parts[1]);
            var year = parseInt(parts[2]);

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

    </script>
</body>
<?php include __DIR__ . '/../footer.php'; ?>

