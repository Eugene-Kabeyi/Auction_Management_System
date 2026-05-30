<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $invoice_id = intval($_POST['invoice_id']);
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $updated_by = $_SESSION['user_id'];

    try {

        $conn->beginTransaction();

        //  UPDATE INVOICE (NOT INSERT)
        $stmt = $conn->prepare("
            UPDATE invoices
            SET 
                status = ?,
                due_date = ?,
                updated_at = NOW()
            WHERE invoice_id = ?
        ");

        $stmt->execute([
            $status,
            $due_date,
            $invoice_id
        ]);

        //  UPDATE PAYMENT STATUS 
        $payment_stmt = $conn->prepare("
            UPDATE payment
            SET payment_status = ?
            WHERE payment_id = ?
        ");

        $payment_stmt->execute([
            $status === 'paid' ? 'completed' : 'pending',
            $payment_id
        ]);

        $conn->commit();

        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['username'],
            "Updated invoice ID: $invoice_id"
        );

        $_SESSION['success'] = "Invoice updated successfully!";
        header("Location: invoice_list.php");
        exit();

    } catch (PDOException $e) {

        $conn->rollBack();

        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['username'],
            "Failed invoice update: " . $e->getMessage()
        );

        $_SESSION['error'] = "Update failed: " . $e->getMessage();
    }
}