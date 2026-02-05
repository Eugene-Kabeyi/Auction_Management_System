<?php include __DIR__ . '/../header.php'; ?>

<body>
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
                <input type="number" name="item_id" id="item_id" required>

                <label>Auctioneer ID</label>
                <input type="number" name="auctioneer_id">

                <label>Start Time</label>
                <input type="text" name="start_time" id="start_time" class ="date_time"required>

                <label>End Time</label>
                <input type="text" name="end_time" id="end_time" class ="date_time" required>

                <label>Status</label>
                <select name="status">
                    <option value="draft">Draft</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <button type="submit">Create Auction</button>
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
    $auctioneer_id = $_SESSION['user_id'] ?? null;
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = $_POST['status'];
    // Here you would typically insert the data into a database
    // For demonstration, we'll just echo the values
      
    // Redirect or display a success message as needed
}
?>    