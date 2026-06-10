<?php
require '../config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please log in to access this page.";
    session_destroy();
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../log_activity.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {

    // Get form data
    $bid_id = $_POST['bid_id'];
    $amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method'];
    $invoice_id = $_POST['invoice_id'];

    $transaction_reference = !empty($_POST['transaction_reference'])
        ? trim($_POST['transaction_reference'])
        : NULL;

    // Check if payment is allowed
    $check_sql = "
        SELECT i.invoice_id
        FROM invoices i
        JOIN auction_bids ab ON i.bid_id = ab.bid_id
        WHERE ab.bid_id = ?
        AND ab.bidder_id = ?
        AND ab.result = 'won'
        AND i.status IN ('unpaid', 'overdue')
        AND NOT EXISTS (
            SELECT 1
            FROM payment p
            WHERE p.invoice_id = i.invoice_id
            AND p.payment_status IN ('pending', 'completed')
        )
    ";

    $check_stmt = mysqli_prepare($conn, $check_sql);

    mysqli_stmt_bind_param(
        $check_stmt,
        "ii",
        $bid_id,
        $user_id
    );

    mysqli_stmt_execute($check_stmt);

    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {

        $insert_sql = "
            INSERT INTO payment
            (
                bid_id,
                bidder_id,
                amount,
                payment_method,
                payment_status,
                transaction_reference,
                payment_date,
                invoice_id
            )
            VALUES
            (?, ?, ?, ?, 'pending', ?, NOW(), ?)
        ";

        $stmt = mysqli_prepare($conn, $insert_sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iidssi",
            $bid_id,
            $user_id,
            $amount,
            $payment_method,
            $transaction_reference,
            $invoice_id
        );

        if (mysqli_stmt_execute($stmt)) {

            logActivity(
                $conn,
                $user_id,
                $_SESSION['username'],
                "Payment initiated for bid ID: " . $bid_id
            );

            $_SESSION['success'] =
                "Payment started successfully! Please wait for staff to process it.";

            header('Location: user_dashboard.php');
            exit();

        } else {

            $_SESSION['error'] = "Error initiating payment.";

            logActivity(
                $conn,
                $user_id,
                $_SESSION['username'],
                "Error initiating payment for bid ID: " . $bid_id
            );

            header('Location: payments.php');
            exit();
        }

    } else {

        $_SESSION['error'] =
            "Invalid bid selected or payment already initiated for this bid.";

        logActivity(
            $conn,
            $user_id,
            $_SESSION['username'],
            "Invalid bid selected for payment initiation: " . $bid_id
        );

        header('Location: payments.php');
        exit();
    }
}
?>