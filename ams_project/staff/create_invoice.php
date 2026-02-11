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
        <a href="invoice_list.php" class="back">Back to Invoice List</a>

        <form action="" method="POST">

            <label>Invoice Number:</label>
            <input type="text" name="invoice_number" required>

            <label>Bidder ID:</label>
            <input type="number" name="bidder_id">

            <label>Payment ID:</label>
            <input type="number" name="payment_id">

            <label>Amount:</label>
            <input type="number" step="0.01" name="amount" required>

            <label>Tax Amount:</label>
            <input type="number" step="0.01" name="tax_amount" value="0.00">

            <label>Total Amount:</label>
            <input type="number" step="0.01" name="total_amount" required>

            <label>Due Date:</label>
            <?php $input_name = 'due_date';
            include __DIR__ . '/../datepicker.php'; ?>

            <label>Status:</label>
            <select name="status">
                <option value="draft">Draft</option>
                <option value="unpaid" selected>Unpaid</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
                <option value="overdue">Overdue</option>
            </select>

            <button type="submit">Add Invoice</button>
        </form>
    </div>
</body>

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