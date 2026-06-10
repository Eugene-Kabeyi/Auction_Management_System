<?php
require __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $payment_id = $_POST['payment_id'];
    $auction_item_id = $_POST['auction_item_id'];
    $net_amount = $_POST['net_amount'];
    $commission_amount = $_POST['commission_amount'];
    $amount_due = $_POST['amount_due'];
    $commission_rate = $_POST['commission_rate'];
    $settlement_date = !empty($_POST['settlement_date']) ? $_POST['settlement_date'] : null;
    $status = $_POST['status'];
    $payment_method = !empty($_POST['payment_method']) ? $_POST['payment_method'] : null;
    $tx_reference = !empty($_POST['transaction_reference']) ? trim($_POST['transaction_reference']) : null;
    $staff_id = $_SESSION['user_id'];

    
    $stmt = mysqli_prepare($conn, "
        INSERT INTO settlements
        (auction_item_id, amount_due, commission_rate, commission_amount, net_amount,
         settlement_date, status, processed_by_staff, payment_method, transaction_reference)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "idddssisss", $auction_item_id, $amount_due, $commission_rate, $commission_amount, $net_amount, $settlement_date, $status, $staff_id, $payment_method, $tx_reference);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        $_SESSION['success'] = "Settlement added successfully";
        logActivity($conn, $staff_id,$_SESSION['username'] ,"Created settlement for payment ID: $payment_id, item ID: $auction_item_id");
        header('Location: settlement_list.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to add settlement";
        logActivity($conn, $staff_id,$_SESSION['username'] ,"Failed to create settlement for payment ID: $payment_id, item ID: $auction_item_id");
        header('Location: create_settlement.php?payment_id=' . $payment_id);
        exit();
    }
}
?>