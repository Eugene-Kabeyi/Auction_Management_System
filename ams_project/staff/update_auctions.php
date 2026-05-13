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

// 2. HANDLE UPDATE

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
?>

<!-- =========================
     FORM UI
========================= -->
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

<form method="post" onsubmit="return validateAuction()">

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