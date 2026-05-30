<?php
require __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access.";
    header("Location: ../staff/staff_login.php");
    exit();
}

// POST data
$payment_id = $_POST['payment_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$payment_id || !$action) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: payments_list.php");
    exit();
}

try {

    // =========================
    // APPROVE PAYMENT
    // =========================
    if ($action === 'approve') {

        // 1. Get related item (via bid -> item OR invoice -> item)
        $stmt = $conn->prepare("
            SELECT ci.item_id
            FROM payment p
            JOIN bids b ON p.bid_id = b.bid_id
            JOIN consigner_items ci ON b.item_id = ci.item_id
            WHERE p.payment_id = ?
        ");
        $stmt->execute([$payment_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Update payment
        $stmt = $conn->prepare("
            UPDATE payment
            SET 
                payment_status = 'completed',
                completed_at = NOW(),
                processed_by_staff = :staff_id
            WHERE payment_id = :payment_id
        ");

        $stmt->execute([
            ':staff_id' => $_SESSION['user_id'],
            ':payment_id' => $payment_id
        ]);

        // 3. Update item status
        if ($item) {
            $stmt = $conn->prepare("
                UPDATE consigner_items
                SET 
                    item_status = 'under_review',
                    updated_at = NOW()
                WHERE item_id = ?
            ");
            $stmt->execute([$item['item_id']]);
        }

        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Approved payment ID $payment_id");

        $_SESSION['success'] = "Payment approved and item moved to review.";
    }

    // =========================
    // REJECT PAYMENT
    // =========================
    elseif ($action === 'reject') {

        // 1. Get related item
        $stmt = $conn->prepare("
            SELECT ci.item_id
            FROM payment p
            JOIN bids b ON p.bid_id = b.bid_id
            JOIN consigner_items ci ON b.item_id = ci.item_id
            WHERE p.payment_id = ?
        ");
        $stmt->execute([$payment_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Update payment
        $stmt = $conn->prepare("
            UPDATE payment
            SET 
                payment_status = 'failed',
                processed_by_staff = :staff_id
            WHERE payment_id = :payment_id
        ");

        $stmt->execute([
            ':staff_id' => $_SESSION['user_id'],
            ':payment_id' => $payment_id
        ]);

        // 3. Update item status (optional rule)
        if ($item) {
            $stmt = $conn->prepare("
                UPDATE consigner_items
                SET 
                    item_status = 'rejected',
                    updated_at = NOW()
                WHERE item_id = ?
            ");
            $stmt->execute([$item['item_id']]);
        }

        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Rejected payment ID $payment_id");

        $_SESSION['success'] = "Payment rejected and item marked accordingly.";
    }

    else {
        $_SESSION['error'] = "Invalid action selected.";
    }

    header("Location: payment_review.php?id=" . $payment_id);
    exit();

} catch (Exception $e) {
    $_SESSION['error'] = "Error processing payment: " . $e->getMessage();
    header("Location: payment_review.php?id=" . $payment_id);
    exit();
}