<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../staff/staff_login.php');
    exit();
}

include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';
include __DIR__ . '/../log_activity.php';

// 1. GET EXISTING AUCTION
if (!isset($_GET['auction_id'])) {
    $_SESSION['error'] = "No auction selected";
    header("Location: staff_dashboard.php");
    exit();
}

$auction_id = $_GET['auction_id'];

$stmt = $conn->prepare("SELECT * FROM auctions WHERE auction_id = :id");
$stmt->bindParam(':id', $auction_id);
$stmt->execute();
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auction) {
    $_SESSION['error'] = "Auction not found";
    header("Location: staff_dashboard.php");
    exit();
}


?>


<head>
    <title>Update Auction</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>

<?php if (isset($_SESSION['error'])): ?>
    <div class="flash error">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<h2>Update Auction</h2>

<div class="outer_container f_container">

<form action="update_auctions_handler.php" method="post" onsubmit="return validateAuction()">

    <label>Auction Name</label>
    <input type="text" name="auction_name" id="auction_name"
           value="<?= $auction['auction_name'] ?>">

    <label>Auction Code</label>
    <input type="text" name="auction_code" id="auction_code"
           value="<?= $auction['auction_code'] ?>">

    <label>Auction Type</label>
    <select name="auction_type">
        <option value="live" <?= $auction['auction_type'] == 'live' ? 'selected' : '' ?>>Live</option>
        <option value="timed" <?= $auction['auction_type'] == 'timed' ? 'selected' : '' ?>>Timed</option>
    </select>

    <label>Item ID</label>
    <select name="item_id">

        <?php
        $sql = "SELECT ei.item_id, i.item_name 
                FROM evaluated_items ei 
                JOIN consigner_items i ON ei.item_id = i.item_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $row) {
            $selected = $row['item_id'] == $auction['item_id'] ? "selected" : "";
            echo "<option value='{$row['item_id']}' $selected>{$row['item_name']}</option>";
        }
        ?>

    </select>

    <label>Start Date</label>
    <input type="text" name="start_date" id="start_date"
           value="<?= date('d/m/Y', strtotime($auction['start_time'])) ?>">

    <label>Start Time</label>
    <input type="text" name="start_time" id="start_time"
           value="<?= date('H:i', strtotime($auction['start_time'])) ?>">

    <label>End Date</label>
    <input type="text" name="end_date" id="end_date"
           value="<?= date('d/m/Y', strtotime($auction['end_time'])) ?>">

    <label>End Time</label>
    <input type="text" name="end_time" id="end_time"
           value="<?= date('H:i', strtotime($auction['end_time'])) ?>">

    <label>Status</label>
    <select name="status">
        <option value="draft" <?= $auction['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="upcoming" <?= $auction['status'] == 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
        <option value="ongoing" <?= $auction['status'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
        <option value="completed" <?= $auction['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
        <option value="cancelled" <?= $auction['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>

    <button type="submit" class="submit">Update Auction</button>

</form>

</div>

<script>
// reuse your same validation functions here
</script>

</body>

<?php include __DIR__ . '/../footer.php'; ?>