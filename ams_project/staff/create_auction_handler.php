<?php
require_once __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';
session_start();

// Handle create_auction
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve form data
    $auction_name = $_POST['auction_name'];
    $auction_code = $_POST['auction_code'];
    $auction_type = $_POST['auction_type'];
    $item_id = $_POST['item_id'];
    $created_by_staff = $_SESSION['user_id'] ?? null;
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = $_POST['status'];

    // Format dates (dd/mm/yyyy → yyyy-mm-dd)
    $start_date = explode("/", $start_date);
    $end_date = explode("/", $end_date);

    $start_date = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
    $end_date = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

    // Clean time
    $start_time = str_replace([" ", ":"], "", $start_time);
    $end_time = str_replace([" ", ":"], "", $end_time);

    $start_datetime = date('Y-m-d H:i:s', strtotime("$start_date $start_time"));
    $end_datetime = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));

    // Validate schedule
    if (strtotime($end_datetime) <= strtotime($start_datetime)) {
        $_SESSION['error'] = "Invalid auction schedule";
        header("Location: create_auction.php");
        exit();
    }

    try {
        mysqli_begin_transaction($conn);

        // 1. CREATE AUCTION

        $stmt = mysqli_prepare($conn, "
            INSERT INTO auctions 
            (auction_name, auction_code, auction_type, item_id, created_by_staff, start_time, end_time, status)
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param($stmt, "sssiisss", $auction_name, $auction_code, $auction_type, $item_id, $created_by_staff, $start_datetime, $end_datetime, $status);
        mysqli_stmt_execute($stmt);


        // 2. UPDATE CONSIGNER ITEM

        $stmt = mysqli_prepare($conn, "
            UPDATE consigner_items
            SET 
                item_status = 'auctioned'
                
            WHERE item_id = ?
        ");

        mysqli_stmt_bind_param($stmt, "i", $item_id);
        mysqli_stmt_execute($stmt);
        
        mysqli_commit($conn);
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['username'],
            "Created auction: $auction_name and marked item $item_id as auctioned"
        );

        $_SESSION['success'] = "Auction created successfully!";
        header("Location: ../staff/staff_dashboard.php");
        exit();

        

    } catch (Exception $e) {
        mysqli_rollback($conn);
        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['username'],
            "Failed to create auction: $auction_name". $e->getMessage()
        );

        $_SESSION['error'] = "Error creating auction: " ;
        header("Location: create_auction.php");
        exit();
    }
}
?>