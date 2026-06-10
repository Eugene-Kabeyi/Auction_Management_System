<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../log_activity.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    $_SESSION['error'] = "Please log in as an staff to access this page.";
    header('Location: ../staff/staff_login.php');
    session_destroy();
    exit();
}

include __DIR__ . '/../config.php';

$payment_id = $_GET['payment_id'] ?? null;

if (!$payment_id) {
    echo "<p>Invalid payment ID</p>";
    exit();
}

$stmt = mysqli_prepare($conn, "
    SELECT *
    FROM payment
    WHERE payment_id = ?
");

mysqli_stmt_execute($stmt, [$payment_id]);
$payment = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$payment) {
    echo "<p>Payment not found</p>";
    exit();
}

?>

<head>
    <title>Review Payment</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="flash success">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="flash error">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="outer_container f_container">
    <h2>Review Payment</h2>

    <a href="invoice_list.php" class="back">Back to Payments</a>


<form method="post" action="approve_payment_handler.php">
    <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">

    <label>Amount:</label>
    <input type="text" value="<?php echo htmlspecialchars($payment['amount']); ?>" disabled>

    <label>Payment Method:</label>
    <input type="text" value="<?php echo htmlspecialchars($payment['payment_method']); ?>" disabled>

    <label>Transaction Reference:</label>
    <input type="text" value="<?php echo htmlspecialchars($payment['transaction_reference']); ?>" disabled>

    <label>Current Status:</label>
    <input type="text" value="<?php echo htmlspecialchars($payment['payment_status']); ?>" disabled>

    <label>Payment Date:</label>
    <input type="text" value="<?php echo htmlspecialchars($payment['payment_date']); ?>" disabled>

    <label>Action:</label>
    <select name="action" id="action">
         <option value="">-- Select Action --</option>
        <option value="approve">Approve</option>
        <option value="reject">Reject</option>
        <option value="refund">Refund</option>
    </select>

    <button type="submit">
        Process Payment
    </button>

</form>

</div>

</body>
<script>
    // Client-side validation for action selection
    function validateForm() {
        var action_select = document.querySelector('select[name="action"]');
        var action = document.getElementById('action').value;
        if (action === "") {
            alert("Please select an action to process the payment.");
            action_select.focus();
            return false;   
        }
        return true;
    }
</script>

<?php include __DIR__ . '/../footer.php'; ?>