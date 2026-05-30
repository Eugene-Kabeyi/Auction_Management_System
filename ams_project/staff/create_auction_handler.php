<?php

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

    // format start and end datetime
    $start_date = explode("/", $start_date);
    $end_date = explode("/", $end_date);
    $start_date = $start_date[2] . "-" . $start_date[1] . "-" . $start_date[0];
    $end_date = $end_date[2] . "-" . $end_date[1] . "-" . $end_date[0];

    //FORMAT start_time and end date_time 
    $start_time = str_replace(" ", "", $start_time);
    $end_time = str_replace(" ", "", $end_time);
    $start_time = str_replace(":", "", $start_time);
    $end_time = str_replace(":", "", $end_time);

    $start_datetime = date('Y-m-d H:i:s', strtotime("$start_date $start_time"));
    $end_datetime = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));

    if (strtotime($end_datetime) <= strtotime($start_datetime)) {
        $_SESSION['error'] = "Invalid auction schedule";
        exit();
    }

    // Here you would typically insert the data into a database
    $tmt = $conn->prepare("INSERT INTO auctions (auction_name, auction_code, auction_type, item_id, created_by_staff, start_time, end_time, status) VALUES (:auction_name, :auction_code, :auction_type, :item_id, :created_by_staff, :start_time, :end_time, :status)");
    $tmt->bindParam(':auction_name', $auction_name);
    $tmt->bindParam(':auction_code', $auction_code);
    $tmt->bindParam(':auction_type', $auction_type);
    $tmt->bindParam(':item_id', $item_id);
    $tmt->bindParam(':created_by_staff', $created_by_staff);
    $tmt->bindParam(':start_time', $start_datetime);
    $tmt->bindParam(':end_time', $end_datetime);
    $tmt->bindParam(':status', $status);

    if ($tmt->execute()) {
        
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Created auction: " . $auction_name);
        $_SESSION['success'] = "Auction created successfully!";
        // Redirect or display a success message as needed
        header("Location: ../staff/staff_dashboard.php");
        exit();
    } else {
        logActivity($conn, $_SESSION['user_id'] ?? null, $_SESSION['username'] ?? 'Unknown', "Failed to create auction: " . $auction_name);
        $_SESSION['error'] = "Error creating auction.";
        echo "<p>Error creating auction.</p>";
    }
}
?>