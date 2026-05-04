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
        <h2>Add New Invoice</h2>
        <!-- <a href="invoice_list.php" class="back">Back to Invoice List</a> -->

        <form id="invoiceForm" action="" method="POST" onsubmit="return validateInvoice()">

            <label>Invoice Number:</label>
            <input type="text" id="invoice_number" name="invoice_number">

            <label>Bidder ID:</label>
            <input type="text" id="bidder_id" name="bidder_id">

            <label>Payment ID:</label>
            <input type="text" id="payment_id" name="payment_id">

            <label>Amount:</label>
            <input type="text" id="amount" name="amount">

            <label>Tax Amount:</label>
            <input type="text" id="tax_amount" name="tax_amount" value="0.00">

            <label>Total Amount:</label>
            <input type="text" id="total_amount" name="total_amount">

            <label>Due Date:</label>
            <input type="text" id="due_date" name="due_date" placeholder="dd/mm/yyyy">

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

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $invoice_number = trim($_POST['invoice_number']);
    $payment_id = !empty($_POST['payment_id']) ? $_POST['payment_id'] : null;
    $bidder_id = !empty($_POST['bidder_id']) ? $_POST['bidder_id'] : null;
    $amount = $_POST['amount'];
    $tax_amount = $_POST['tax_amount'];
    $total_amount = $_POST['total_amount'];
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $status = $_POST['status'];
    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare(
        "INSERT INTO invoices 
        (invoice_number, payment_id, bidder_id, amount, tax_amount, total_amount, due_date, status, created_by_staff)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $success = $stmt->execute([
        $invoice_number,
        $payment_id,
        $bidder_id,
        $amount,
        $tax_amount,
        $total_amount,
        $due_date,
        $status,
        $created_by
    ]);

    if ($success) {
        $_SESSION['success'] = "Invoice $invoice_number added successfully";
        header('Location: invoice_list.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to add invoice";
    }
}
?>