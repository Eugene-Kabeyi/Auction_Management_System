<?php include __DIR__ . '/../header.php';
include __DIR__ . '/../log_activity.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    if (isset($_SESSION['login_type']) && ($_SESSION['login_type'] === 'admin' || $_SESSION['login_type'] === 'staff')) {
        header("Location: ../staff/staff_dashboard.php");
        exit();
    }
    header("Location: login.php");
    session_destroy();
    $_SESSION['error'] = "Please log in as a user to access this page.";
    exit();
}

include __DIR__ . '/../config.php';

$auction_id = $_GET['auction_id'] ?? null;
// GET AUCTION TIME
$stmt = $conn->prepare("
    SELECT end_time FROM auctions WHERE auction_id = :auction_id
");
$stmt->execute(['auction_id' => $auction_id]);
$auctionData = $stmt->fetch();

$current_time = date("Y-m-d H:i:s");

//CHECK IF ALREADY DONE

$stmt = $conn->prepare("
    SELECT COUNT(*) as c 
    FROM auction_bids 
    WHERE auction_id = :auction_id AND result != 'pending'
");
$stmt->execute(['auction_id' => $auction_id]);
$alreadyFinalized = $stmt->fetch()['c'];


// FINALIZE AUCTION

if ($current_time > $auctionData['end_time'] && $alreadyFinalized == 0) {
    

    $conn->beginTransaction();

    try {

        //SET WINNER + LOSERS

        $stmt = $conn->prepare("
            UPDATE auction_bids
            SET result = CASE 
                WHEN bid_status = 'winning' THEN 'won'
                ELSE 'lost'
            END
            WHERE auction_id = :auction_id
        ");
        $stmt->execute(['auction_id' => $auction_id]);

        $conn->commit();
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Auction finalized for auction ID: " . $auction_id);

    } catch (Exception $e) {
        $conn->rollBack();
        if ($loginType === 'admin' || $loginType === 'staff') {

            $_SESSION['error'] = "Error finalizing auction.";
        }
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Error finalizing auction for auction ID: " . $auction_id . " - " . $e->getMessage());
        header("Location: live_auction.php?auction_id=" . $auction_id);
        exit();
       
    }
}
?>

<?php

// Handle bid submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid_amount = $_POST['bid_amount'];
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session 
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

<head>
    <title>Live Auction</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #ebe9e9;
        }

        .outer_container {
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: center;
            margin: 20px auto;

        }

        .header_container {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 50px;
        }

        .outer_container {
            display: flex;
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
            margin-bottom: 200px;
        }

        .pic_inner_container {
            flex: 1;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            justify-content: center;
            display: flex;
            align-items: center;
            background-color: #ffffff;
        }

        .details_inner_container {
            flex: 1;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color: #ffffff;
        }

        .details_inner_container h3 {
            margin-top: 0;
        }

        .details_inner_container p {
            margin: 10px 0;
        }

        #bidButton,
        #exitBidButton {
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }

        #bidButton:hover,
        #exitBidButton:hover {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #1f2933;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            border-radius: 5px;
            height: auto;
        }

        .modal-content h2,
        .modal-content label {
            margin: 0;
            margin-top: 0;
            text-align: center;
        }

        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .modal-content input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .modal-content button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #1f2933;
            color: #ffffff;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;

        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .flash {
            z-index: 2;
            position: fixed;
            top: 20px;
            left: 20px;
            min-width: 260px;
            padding: 14px 18px;
            border-radius: 6px;
            font-size: 14px;
            z-index: 9999;
            animation: slideIn 0.4s ease, fadeOut 0.4s ease 4s forwards;
        }

        /* Flash message styles */
        .flash.error {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 5px solid #ef4444;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        }


        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(-30px);
            }
        }
    </style>
</head>

<body style="height: 100%;">
    <?php
    // Fetch auction and item details from the database
    $auction_id = $_GET['auction_id'] ?? null; // Get auction ID from URL
    if ($auction_id) {
        $stmt = $conn->prepare("SELECT a.item_id, a.auction_name, a.auction_code, a.start_time, a.end_time, i.item_name, i.item_description, e.reserve_price, i.image_path FROM auctions a JOIN consigner_items i ON a.item_id = i.item_id JOIN evaluated_items e ON i.item_id = e.item_id WHERE a.auction_id = :auction_id");
        $stmt->execute(['auction_id' => $auction_id]);
        $auction = $stmt->fetch();
        if ($auction) {
            $auction_name = $auction['auction_name'];
            $auction_code = $auction['auction_code'];
            $item_name = $auction['item_name'];
            $item_description = $auction['item_description'];
            $starting_bid = $auction['reserve_price'];
            $item_image_url = $auction['image_path'];
            $start_time = date("F j, Y, g:i a", strtotime($auction['start_time'])); // Format start time for display
            $end_time = date("F j, Y, g:i a", strtotime($auction['end_time'])); // Format end time for display
    
        } else {
            echo "<p>Auction not found.</p>";
            exit();
        }
    } else {
        echo "<p>No auction specified.</p>";
        exit();
    }
    // Fetch current highest bid
    $stmt = $conn->prepare("SELECT MAX(amount_bidded) AS highest_bid FROM auction_bids WHERE auction_id = :auction_id");
    $stmt->execute(['auction_id' => $auction_id]);
    $bid_result = $stmt->fetch();

    $current_highest_bid = $bid_result['highest_bid'] ?? 0;
    $minimum_bid = ($current_highest_bid > 0) ? $current_highest_bid + 1 : $starting_bid; // Minimum bid must be at least 1 unit higher than current highest
    ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (strtotime($auction['end_time']) <= time()) {
        $_SESSION['error'] = "Auction has ended.";
    } ?>


    <div class="header_container">
        <h2>Welcome to <?php echo $auction_name; ?></h2>
        <p>Auction Code: <?php echo $auction_code; ?></p>
    </div>
    <div class="outer_container">
        <!--Picture of auctioned item -->
        <div class="pic_inner_container">

            <img src="<?php echo $item_image_url; ?>" alt="Auctioned Item Image" style="max-width:100%; height:auto; ">
        </div>
        <!--Details of auctioned item -->
        <div class="details_inner_container">
            <h2 id="countDown">Time Remaining: <span id="countdownTimer">00:00:00</span></h2>
            <h3>Item Details</h3>
            <p><strong>Item Name:</strong> <?php echo $item_name; ?></p>
            <p><strong>Description:</strong> <?php echo $item_description; ?></p>
            <p><strong>Starting Bid:</strong> ksh <?php echo number_format($starting_bid, 2); ?></p>
            <p><strong> <span id="c_change">Current</span> Highest Bid:</strong> ksh
                <?php echo number_format($current_highest_bid, 2); ?>
            </p>
            <p><strong>Auction <span id="end_change">Ends </span> At:</strong> <?php echo $end_time; ?></p>

            <!-- Bid Submission Modal Trigger Button and Exit Bid Button -->
            <div id="auctionDetails">
                <button id="bidButton">Place Your Bid</button>
                <button id="exitBidButton">Exit Bid</button>
            </div>
        </div>
    </div>

    <!-- Bid Submission Modal -->
    <div id="bidModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Place Your Bid</h2>

            <!--Form bid submission-->
            <form id="bidForm" action="" method="post">
                <!-- Minimum bid amount is dynamically set based on current highest bid or starting bid -->
                <label for="bid_amount">Bid Amount (Minimum: ksh <?php echo number_format($minimum_bid, 2); ?>):</label>
                <input type="text" id="bid_amount" name="bid_amount" placeholder="Enter your bid amount" required
                    onmouseout="validateBid()">
                <!-- Error message display for bid validation -->
                <div id="errorMsg" class="error"></div>
                <button type="submit" name="submit">Submit Bid</button>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../footer.php'; ?>
    <script>
        // Get modal element
        var modal = document.getElementById("bidModal");
        // Get open modal button
        var bidBtn = document.getElementById("bidButton");
        // Get close button
        var closeBtn = document.getElementsByClassName("close")[0];
        // Get exit bid button
        var exitBidBtn = document.getElementById("exitBidButton");
        // Get form 
        var form = document.getElementById("bidForm");
        // Get bid amount input
        var bidInput = document.getElementById("bid_amount");
        // Get error message element
        var errorMsg = document.getElementById("errorMsg");
        //Full Countdown text element
        var countDown = document.getElementById("countDown");
        // Countdown timer
        var countdownTimer = document.getElementById("countdownTimer");
        // Auction container
        var auctionDetails = document.getElementById("auctionDetails");
        // Change text elements for better UX
        var cChange = document.getElementById("c_change");
        var endChange = document.getElementById("end_change");

        // Listen for open click
        bidBtn.onclick = function () {
            modal.style.display = "block";
        }

        // Listen for close click
        closeBtn.onclick = function () {
            modal.style.display = "none";
        }

        // Listen for exit bid click
        exitBidBtn.onclick = function () {
            window.location.href = "user_dashboard.php"; // Redirect to another page
        }

        // Listen for outside click
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        // Minimum bid from PHP
        var minimumBid = <?php echo $minimum_bid; ?>;
        // Listen for form submission
        function validateBid() {
            var bidValue = parseFloat(bidInput.value);

            // Clear previous error
            //errorMsg.textContent = "";

            // Validation checks
            if (isNaN(bidValue)) {
                //alert("Please enter a valid number.");
                event.preventDefault();
                alert("Please enter a valid number.");
                return;
            }

            if (bidValue < minimumBid) {
                //alert("Bid must be at least Ksh" + minimumBid.toFixed(2));
                event.preventDefault();
                alert("Bid must be at least Ksh " + minimumBid.toFixed(2));
                return;
            }

            if (bidValue <= 0) {
                //alert("Bid must be greater than 0.");
                event.preventDefault();
                alert("Bid must be greater than 0.");
                return;
            }

        };

        //Disable auctiondetails for admin and staff
        var userRole = "<?php echo $_SESSION['login_type']; ?>";
        if (userRole === 'staff' || userRole === 'admin') {
            auctionDetails.style.display = "none";
            auctionDetails.style.pointerEvents = "none";
        }
        //Disable bid button and form for staff and admin
        if (userRole === 'staff' || userRole === 'admin') {
            bidBtn.style.display = "none";
            bidBtn.style.pointerEvents = "none";
            form.style.display = "none";
            form.style.pointerEvents = "none";
            alert("Staff and admin users cannot place bids.");
        }

        // Countdown timer logic
        document.addEventListener("DOMContentLoaded", function () {
            var auctionEndTime = new Date("<?php echo $auction['end_time']; ?>").getTime();
            var countdownInterval = setInterval(function () {

                var now = new Date().getTime();
                var distance = auctionEndTime - now;

                //If time is up
                if (distance <= 0) {
                    clearInterval(countdownInterval);
                    countDown.style.display = "none";
                    auctionDetails.textContent = "Auction Ended";
                    auctionDetails.style.color = "red";
                    auctionDetails.style.textAlign = "center";
                    auctionDetails.style.fontSize = "24px";
                    cChange.style.display = "none";
                    endChange.textContent = "Ended";

                    // Disable bidding
                    bidInput.disabled = true;
                    document.querySelector("#bidForm button").disabled = true;

                    return;

                }
                if (distance < 60000 && distance > 0) { // less than 1 minute
                    countdownTimer.style.color = "red";
                    alert("Auction ending in less than 1 minute! Place your bid now.");
                }
                else if (distance < 300000 && distance > 0) { // less than 5 minutes
                    countdownTimer.style.color = "orange";
                    alert("Auction ending in less than 5 minutes! Place your bid soon.");
                }

                // Time calculations
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display
                countdownTimer.textContent =
                    hours + "h " + minutes + "m " + seconds + "s";

            }, 1000);
        });
    </script>