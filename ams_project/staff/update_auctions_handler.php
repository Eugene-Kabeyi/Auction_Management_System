<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $auction_name = $_POST['auction_name'];
    $auction_code = $_POST['auction_code'];
    $auction_type = $_POST['auction_type'];
    $item_id = $_POST['item_id'];
    $status = $_POST['status'];

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Convert date format dd/mm/yyyy → yyyy-mm-dd
    $start_date = explode("/", $start_date);
    $end_date = explode("/", $end_date);

    $start_date = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
    $end_date = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

    // Clean time input
    $start_time = str_replace(" ", "", $start_time);
    $end_time = str_replace(" ", "", $end_time);

    $start_datetime = date('Y-m-d H:i:s', strtotime("$start_date $start_time"));
    $end_datetime = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));

    // Validation: end must be after start
    if (strtotime($end_datetime) <= strtotime($start_datetime)) {
        $_SESSION['error'] = "Invalid auction schedule";
        header("Location: update_auction.php?auction_id=$auction_id");
        exit();
    }

    // 3. UPDATE QUERY
    $stmt = $conn->prepare("
        UPDATE auctions 
        SET 
            auction_name = :auction_name,
            auction_code = :auction_code,
            auction_type = :auction_type,
            item_id = :item_id,
            start_time = :start_time,
            end_time = :end_time,
            status = :status
        WHERE auction_id = :auction_id
    ");

    $stmt->bindParam(':auction_name', $auction_name);
    $stmt->bindParam(':auction_code', $auction_code);
    $stmt->bindParam(':auction_type', $auction_type);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':start_time', $start_datetime);
    $stmt->bindParam(':end_time', $end_datetime);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':auction_id', $auction_id);

    if ($stmt->execute()) {

        logActivity(
            $conn,
            $_SESSION['user_id'],
            $_SESSION['username'],
            "Updated auction: " . $auction_name
        );

        $_SESSION['success'] = "Auction updated successfully!";
        header("Location: staff_dashboard.php");
        exit();

    } else {
        $_SESSION['error'] = "Failed to update auction";
    }
}