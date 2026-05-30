<?php
require '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: login.php");
    session_destroy();
    exit();
}   
include __DIR__ . '/../log_activity.php';

// Handle bid submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid_amount = $_POST['bid_amount'];
    $user_id = $_SESSION['user_id']; 
    if ($bid_amount < $minimum_bid) {
        $_SESSION['error'] = "Bid must be at least $minimum_bid";
        header("Location: live_auction.php?auction_id=" . $auction_id);
        exit();
    }
    // Insert bid into database with transaction to ensure data integrity
    $conn->beginTransaction();

    try {
        // 1. Mark exisying winning bid as outbid
        $stmt = $conn->prepare("UPDATE auction_bids SET bid_status = 'outbid' WHERE auction_id = :auction_id AND bid_status = 'winning'");
        $stmt->execute(['auction_id' => $auction_id]);

        // 2. Insert new bid as winning
        $stmt = $conn->prepare("INSERT INTO auction_bids (auction_id, bidder_id, amount_bidded, bid_status) VALUES (:auction_id, :user_id, :amount_bidded, 'winning')");
        $stmt->execute([
            'auction_id' => $auction_id,
            'user_id' => $user_id,
            'amount_bidded' => $bid_amount
        ]);

        $conn->commit();
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Placed a bid of Ksh " . number_format($bid_amount, 2) . " on auction ID: " . $auction_id);

        // Redirect
        header("Location: live_auction.php?auction_id=" . $auction_id);
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Error placing bid.";
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Error placing bid on auction ID: " . $auction_id . " - " . $e->getMessage());
        header("Location: live_auction.php?auction_id=" . $auction_id);
        exit();
    }


} ?>