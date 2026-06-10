<?php
require __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    $_SESSION['error'] = "Unauthorized access.";
    header("Location: ../staff/staff_login.php");
    exit();
}

$payment_id = $_POST['payment_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$payment_id || !$action) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: payments_list.php");
    exit();
}

mysqli_begin_transaction($conn);

try {

    // STEP 1: GET RELATED ITEM THROUGH CORRECT CHAIN
    $stmt = mysqli_prepare($conn, "
        SELECT ci.item_id
        FROM payment p
        JOIN auction_bids ab ON p.bid_id = ab.bid_id
        JOIN auctions a ON ab.auction_id = a.auction_id
        JOIN consigner_items ci ON a.item_id = ci.item_id
        WHERE p.payment_id = ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param($stmt, "i", $payment_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);

    // =========================
    // APPROVE PAYMENT
    // =========================
    if ($action === 'approve') {

        // update payment
        $stmt = mysqli_prepare($conn, "
            UPDATE payment
            SET payment_status = 'completed',
                completed_at = NOW(),
                processed_by_staff = ?
            WHERE payment_id = ?
        ");

        mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $payment_id);
        mysqli_stmt_execute($stmt);

        // update item
        if ($item) {
            $stmt = mysqli_prepare($conn, "
                UPDATE consigner_items
                SET item_status = 'sold',
                    updated_at = NOW()
                WHERE item_id = ?
            ");

            mysqli_stmt_bind_param($stmt, "i", $item['item_id']);
            mysqli_stmt_execute($stmt);
        }

        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'],
            "Approved payment ID $payment_id");

        $_SESSION['success'] = "Payment approved successfully.";
    }

    
    // REJECT PAYMENT
    
    elseif ($action === 'reject') {

        $stmt = mysqli_prepare($conn, "
            UPDATE payment
            SET payment_status = 'failed',
                processed_by_staff = ?
            WHERE payment_id = ?
        ");

        mysqli_stmt_bind_param($stmt, "ii", $_SESSION['user_id'], $payment_id);
        mysqli_stmt_execute($stmt);

        if ($item) {
            $stmt = mysqli_prepare($conn, "
                UPDATE consigner_items
                SET item_status = 'returned',
                   
                WHERE item_id = ?
            ");

            mysqli_stmt_bind_param($stmt, "i", $item['item_id']);
            mysqli_stmt_execute($stmt);
        }

        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'],
            "Rejected payment ID $payment_id");

        $_SESSION['success'] = "Payment rejected successfully.";
    }

    else {
        throw new Exception("Invalid action");
    }

    mysqli_commit($conn);

    header("Location: payment_review.php?id=" . $payment_id);
    exit();

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = "Error processing payment.";

    logActivity(
        $conn,
        $_SESSION['user_id'],
        $_SESSION['username'],
        "Payment error ID $payment_id: " . $e->getMessage()
    );

    header("Location: staff_dashboard.php?id=" . $payment_id);
    exit();
}
?>