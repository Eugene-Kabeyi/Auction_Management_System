<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../staff/staff_login.php');
    exit();
}
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

?>


<head>
    <style>
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 640px;
            margin: 0 auto;
            justify-content: center;
        }

        .f_inner_container {
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .s_inner_container {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form label {
            font-weight: bold;
        }

        form input,
        form textarea,
        form select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form .submit {
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="flash error">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h2>Create Auction</h2>
    <div class="outer_container">
        <div class="f_inner_container"></div>
        <div class="s_inner_container">
            <form action="create_auction.php" method="post">
                <label for="auction_name">Auction Name:</label>
                <input type="text" id="auction_name" name="auction_name" required>

                <label>Auction Code</label>
                <input type="text" name="auction_code" id="auction_code" required>

                <label>Auction Type</label>
                <select name="auction_type">
                    <option value="live">Live</option>
                    <option value="timed">Timed</option>
                </select>

                <label>Item ID</label>
                <select name="item_id">
                    <!--php fetch for items in evaluate_items-->
                    <?php
                    $sql = "SELECT ei.item_id, i.item_name, i.consigner_id  FROM evaluated_items ei JOIN consigner_items i ON ei.item_id = i.item_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);


                    if (count($items) > 0) {
                        foreach ($items as $row) {
                            echo "<option value='{$row['item_id']}'>{$row['item_name']}</option>";
                        }
                    } else {
                        echo "<option value=''>No items available</option>";
                    }

                    ?>
                </select>

                <label>Start Date</label>
                <?php $input_name = 'start_date';
                include __DIR__ . '/../datepicker.php'; ?>

                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="start_time" required>

                <label>End Date</label>
                <?php $input_name = 'end_date';
                include __DIR__ . '/../datepicker.php'; ?>

                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="end_time" required>

                <label>Status</label>
                <select name="status">
                    <option value="draft">Draft</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <button type="submit" class="submit">Create Auction</button>
            </form>
        </div>
    </div>

    <script>

    </script>
</body>
<?php include __DIR__ . '/../footer.php'; ?>\
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
    $start_datetime = date('Y-m-d H:i:s', strtotime("$start_date $start_time"));
    $end_datetime = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));

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
        echo "<p>Auction created successfully!</p>";
        $_SESSION['success'] = "Auction created successfully!";
        // Redirect or display a success message as needed
        header("Location: ../staff/staff_dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error creating auction.";
        echo "<p>Error creating auction.</p>";
    }
}
?>