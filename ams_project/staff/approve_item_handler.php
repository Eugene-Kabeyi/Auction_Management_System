<?php
require __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $item_id = $_POST['item_id'] ;
    $action = $_POST['action'];
    $rating = $_POST['rating'] ;
    $authenticity = $_POST['authenticity'] ;
    $reserve_price = $_POST['reserved_price'] ;
    $eval_notes = $_POST['eval_notes'] ;

    $date = $_POST['evaluation_date'] ?? date('d/m/Y');

    // Convert date dd/mm/yyyy → yyyy-mm-dd
    $date_parts = explode('/', $date);
    if (count($date_parts) === 3) {
        $date = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    } else {
        $date = date('Y-m-d');
    }

    // Basic validation
    if (empty($item_id) || empty($_POST['eval_notes']) || empty($_POST['reserved_price']) || $_POST['reserved_price'] <= 0) {
        $_SESSION['error'] = "Invalid evaluation data";
        header("Location: approve_item.php");
        exit();
    }

    try {

        // INSERT EVALUATION 
        $stmt = mysqli_prepare($conn, "
            INSERT INTO evaluated_items
            (item_id, evaluator_id, evaluation_date, condition_rating, authenticity_status, reserve_price, evaluation_notes, final_decision)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param($stmt, "iisssdss", $item_id, $_SESSION['user_id'], $date, $rating, $authenticity, $reserve_price, $eval_notes, $action);
        mysqli_stmt_execute($stmt);
          


        // UPDATE ITEM STATUS
        if ($action === 'approved') {

            $new_status = 'approved';
            $log_msg = "Approved item ID: $item_id";
            $success_msg = "Item approved successfully!";

        } elseif ($action === 'rejected') {

            $new_status = 'rejected';
            $log_msg = "Rejected item ID: $item_id";
            $success_msg = "Item rejected successfully!";

        } else {
            $_SESSION['error'] = "Invalid action";
            header("Location: staff_dashboard.php");
            exit();
        }

        $stmt = mysqli_prepare($conn, " UPDATE consigner_items SET item_status = ?, updated_at = NOW() WHERE item_id = ? ");

        mysqli_stmt_bind_param($stmt, "si", $new_status, $item_id);
        mysqli_stmt_execute($stmt);


        // LOG ACTIVITY

        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], $log_msg);

        $_SESSION['success'] = $success_msg;
        header("Location: staff_dashboard.php");
        exit();

    } catch (Exception $e) {
        logActivity($conn, $_SESSION['user_id'] , $_SESSION['username'] , "Evaluation failed for item ID: $item_id". $e->getMessage());

        $_SESSION['error'] = "Error processing evaluation " ;
        header("Location: approve_item.php?item_id=" . $item_id);
        exit();
    }
}
?>