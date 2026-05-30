<?php
require __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $item_id = $_POST['item_id'] ?? null;
    $action = $_POST['action'] ?? null;

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
        $stmt = $conn->prepare("
            INSERT INTO evaluated_items
            (item_id, evaluator_id, evaluation_date, condition_rating, authenticity_status, reserve_price, evaluation_notes, final_decision)
            VALUES
            (:item_id, :evaluator_id, :evaluation_date, :condition_rating, :authenticity_status, :reserve_price, :evaluation_notes, :final_decision)
        ");

        $stmt->execute([
            ':item_id' => $item_id,
            ':evaluator_id' => $_SESSION['user_id'],
            ':evaluation_date' => $date,
            ':condition_rating' => $_POST['rating'] ?? null,
            ':authenticity_status' => $_POST['authenticity'] ?? null,
            ':reserve_price' => $_POST['reserved_price'],
            ':evaluation_notes' => $_POST['eval_notes'],
            ':final_decision' => $action
        ]);


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

        $stmt = $conn->prepare(" UPDATE consigner_items SET item_status = :status, updated_at = NOW() WHERE item_id = :item_id ");

        $stmt->execute([':status' => $new_status, ':item_id' => $item_id]);


        // LOG ACTIVITY

        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], $log_msg);

        $_SESSION['success'] = $success_msg;
        header("Location: staff_dashboard.php");
        exit();

    } catch (Exception $e) {
        logActivity($conn, $_SESSION['user_id'] , $_SESSION['username'] , "Evaluation failed for item ID: $item_id");

        $_SESSION['error'] = "Error processing evaluation: " . $e->getMessage();
        header("Location: staff_dashboard.php");
        exit();
    }
}
?>