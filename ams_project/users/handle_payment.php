<?php
// This part runs when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['initiate_payment'])) {

    // Get form data
    $bid_id = $_POST['bid_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $invoice_id = $_POST['invoice_id'];
    $transaction_reference = !empty($_POST['transaction_reference']) ? trim($_POST['transaction_reference']) : null;
    $check_stmt = $conn->prepare("
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
");

    $check_stmt->execute([$bid_id, $user_id]);


    if ($check_stmt->rowCount() > 0) {
        // Save payment to database with status 'pending'
        $stmt = $conn->prepare("
            INSERT INTO payment 
            (bid_id, bidder_id, amount, payment_method, payment_status, transaction_reference, payment_date, invoice_id)
            VALUES (?, ?, ?, ?, 'pending', ?, NOW(), ?)
        ");

        try {
            $stmt->execute([
                $bid_id,
                $user_id,
                $amount,
                $payment_method,
                $transaction_reference,
                $invoice_id
            ]);
            logActivity($conn, $user_id, $_SESSION['username'], "Payment initiated for bid ID: " . $bid_id);

            $_SESSION['success'] = "Payment started successfully! Please wait for staff to process it.";
            header('Location: payments_history.php');
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error initiating payment: ";
            logActivity($conn, $user_id, $_SESSION['username'], "Error initiating payment for bid ID: " . $bid_id . " - " . $e->getMessage());
            header('Location: payments.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid bid selected or payment already initiated for this bid.";
        logActivity($conn, $user_id, $_SESSION['username'], "Invalid bid selected for payment initiation: " . $bid_id);
        header('Location: payments.php');
        exit();
    }
}


?>