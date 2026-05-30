<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'staff') {
    header("Location: ../login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT 
        p.payment_id,
        p.bid_id,
        p.bidder_id,
        p.amount,
        p.payment_method,
        p.transaction_reference,
        p.payment_status,
        p.payment_date,
        a.auction_name
    FROM payment p
    JOIN auction_bids ab ON ab.bid_id = p.bid_id
    JOIN auctions a ON a.auction_id = ab.auction_id
    WHERE p.payment_status = 'pending'
    ORDER BY p.payment_date DESC
");
$stmt->execute();
$payments = $stmt->fetchAll();
?>