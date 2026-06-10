<?php
session_start();

include __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    $_SESSION['error'] = "Unauthorized.";
    header("Location: ../staff/staff_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: settlement_list.php");
    exit();
}

$settlement_id = intval($_POST['settlement_id']);
$settlement_date = $_POST['settlement_date'];
$status = $_POST['status'];
$payment_method = $_POST['payment_method'];
$transaction_reference = trim($_POST['transaction_reference']);
$staff_id = $_SESSION['user_id'];

try {

    mysqli_begin_transaction($conn);

    $stmt = mysqli_prepare($conn,"
        UPDATE settlement
        SET
            settlement_date = ?,
            status = ?,
            processed_by_staff = ?,
            payment_method = ?,
            transaction_reference = ?
        WHERE settlement_id = ?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "ssissi",
        $settlement_date,
        $status,
        $staff_id,
        $payment_method,
        $transaction_reference,
        $settlement_id
    );

    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);

    logActivity(
        $conn,
        $_SESSION['user_id'],
        $_SESSION['username'],
        "Updated settlement ID: $settlement_id"
    );

    $_SESSION['success'] =
        "Settlement updated successfully.";

} catch (Exception $e) {

    mysqli_rollback($conn);

    logActivity(
        $conn,
        $_SESSION['user_id'],
        $_SESSION['username'],
        "Settlement update failed: " . $e->getMessage()
    );

    $_SESSION['error'] =
        "Failed to update settlement.";
}

header("Location: settlement_list.php");
exit();
?>