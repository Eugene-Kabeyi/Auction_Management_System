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
    <a href="settlement_list.php" class="back">Back to Settlement List</a>

    <form action="" method="POST">

        <label>Auction Item ID:</label>
        <input type="number" name="auction_item_id" required>

        <label>Amount Due:</label>
        <input type="number" step="0.01" name="amount_due" required>

        <label>Commission Rate (%):</label>
        <input type="number" step="0.01" name="commission_rate" value="15.00" required>

        <label>Settlement Date:</label>
        <input type="date" name="settlement_date">

        <label>Payment Method:</label>
        <select name="payment_method">
            <option value="">-- Select --</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cheque">Cheque</option>
            <option value="mobile_money">Mobile Money</option>
        </select>

        <label>Transaction Reference:</label>
        <input type="text" name="transaction_reference">

        <label>Status:</label>
        <select name="status">
            <option value="pending" selected>Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <button type="submit">Add Settlement</button>
    </form>
</div>
</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $auction_item_id = $_POST['auction_item_id'];
    $amount_due      = $_POST['amount_due'];
    $commission_rate = $_POST['commission_rate'];
    $settlement_date = !empty($_POST['settlement_date']) ? $_POST['settlement_date'] : null;
    $status          = $_POST['status'];
    $payment_method  = !empty($_POST['payment_method']) ? $_POST['payment_method'] : null;
    $tx_reference    = !empty($_POST['transaction_reference']) ? trim($_POST['transaction_reference']) : null;
    $staff_id        = $_SESSION['user_id'];

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
