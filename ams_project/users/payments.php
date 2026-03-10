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
        <h2>Add New Payment</h2>
        <a href="payment_list.php" class="back">Back to Payment List</a>

        <form action="" method="POST">

            <label>Bid ID:</label>
            <select name="bid_id" required>
                <option value="">Select Bid</option>
                <input type="text" name="bid_id">
            </select>

            <label>Bidder ID:</label>
            <select name="bidder_id" required>
                <option value="">Select Bidder</option>
                <input type="text" name="bidder_id" id="">
            </select>

            <label>Amount:</label>
            <input type="number" step="0.01" name="amount" required>

            <label>Payment Method:</label>
            <select name="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="credit_card">Credit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="mobile_money">Mobile Money</option>
                <option value="cash">Cash</option>
            </select>

            <label>Payment Status:</label>
            <select name="payment_status" required>
                <option value="pending" selected>Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>

            <label>Transaction Reference:</label>
            <input type="text" name="transaction_reference" placeholder="Optional">

            <label>Payment Date:</label>
            <?php $input_name = 'payment_date';
            include __DIR__ . '/../datepicker.php'; ?>

            <label>Completed At:</label>
            <?php $input_name = 'completed_at';
            include __DIR__ . '/../datepicker.php'; ?>

            <button type="submit">Add Payment</button>
        </form>
    </div>
</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bid_id = $_POST['bid_id'];
    $bidder_id = $_POST['bidder_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];
    $transaction_reference = !empty($_POST['transaction_reference']) ? trim($_POST['transaction_reference']) : null;
    $payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : date('Y-m-d H:i:s');
    $completed_at = !empty($_POST['completed_at']) ? $_POST['completed_at'] : null;
    $processed_by_staff = $_SESSION['user_id'];

    $stmt = $conn->prepare(
        "INSERT INTO payments 
        (bid_id, bidder_id, amount, payment_method, payment_status, transaction_reference, payment_date, completed_at, processed_by_staff)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    try {
        $success = $stmt->execute([
            $bid_id,
            $bidder_id,
            $amount,
            $payment_method,
            $payment_status,
            $transaction_reference,
            $payment_date,
            $completed_at,
            $processed_by_staff
        ]);

        if ($success) {
            $_SESSION['success'] = "Payment added successfully";
            header('Location: payment_list.php');
            exit();
        } else {
            $_SESSION['error'] = "Failed to add payment";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
}
?>