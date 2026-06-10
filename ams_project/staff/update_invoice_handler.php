<?php
session_start();
require __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $invoice_id = intval($_POST['invoice_id']);
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $updated_by = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $tax_amount = $_POST['tax_amount'];
    $total_amount = $_POST['total_amount'];
    
    try {

        mysqli_begin_transaction($conn);

        //  UPDATE INVOICE (INSERT)
        $stmt = mysqli_prepare($conn, "
            UPDATE invoices
            SET 
                status = ?,
                due_date = ?,
                created_by_staff = ?,
                updated_at = NOW()
            WHERE invoice_id = ?
        ");

        mysqli_stmt_bind_param($stmt, "ssii", $status, $due_date, $updated_by, $invoice_id);
        mysqli_stmt_execute($stmt);

        //  UPDATE PAYMENT STATUS 
        $payment_stmt = mysqli_prepare($conn, "
            UPDATE payment
            SET 
                payment_status = ?,
                amount = ?,
                processed_by_staff = ?
            WHERE invoice_id = ?
            
        ");
        $payment_status = match ($status) {

            'paid' => 'completed',

            'cancelled' => 'failed',

            'overdue' => 'failed',   // optional but more realistic

            'unpaid' => 'pending',

            'draft' => 'pending',

            default => 'pending'
        };
        mysqli_stmt_bind_param($payment_stmt, "sdii", $payment_status, $total_amount, $updated_by, $invoice_id);
        mysqli_stmt_execute($payment_stmt);



        mysqli_commit($conn);

        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['username'],
            "Updated invoice ID: $invoice_id"
        );

        $_SESSION['success'] = "Invoice updated successfully!";
        header("Location: invoice_list.php");
        exit();

    } catch (mysqli_sql_exception $e) {

        mysqli_rollback($conn);

        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['username'],
            "Failed invoice update: " . $e->getMessage()
        );

        $_SESSION['error'] = "Update failed: " . $e->getMessage();
    }
}