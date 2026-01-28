<?php include __DIR__ . '/../header.php'; ?>
<body>
    <div class = "header_container">
    <h2>Welcome to <?php echo $auction_name; ?></h2>
    <p>Auction Code: <?php echo $auction_code; ?></p>
    </div>
    <div class="outer_container">
        <!--Picture of auctioned item -->
        <div class="pic_inner_container">
            <img src="<?php echo $item_image_url; ?>" alt="Auctioned Item Image" style="max-width:100%; height:auto;">
        </div>
        <!--Details of auctioned item -->
        <div class="details_inner_container">
            <h3>Item Details</h3>
            <p><strong>Item Name:</strong> <?php echo $item_name; ?></p>
            <p><strong>Description:</strong> <?php echo $item_description; ?></p>
            <p><strong>Starting Bid:</strong> $<?php echo number_format($starting_bid, 2); ?></p>
            <p><strong>Current Highest Bid:</strong> $<?php echo number_format($current_highest_bid, 2); ?></p>
            <p><strong>Auction Ends At:</strong> <?php echo $end_time; ?></p>   

            <!-- Bid Submission Modal Trigger Button and Exit Bid Button -->
             <div>
            <button id="bidButton">Place Your Bid</button>
            <button id = "exitBidButton">Exit Bid</button>
            </div>
        </div>
    </div>  
    <!-- Bid Submission Modal -->
    <div id="bidModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Place Your Bid</h2>
            <form action="handle_bid.php" method="post">
                <label for="bid_amount">Bid Amount (Minimum: $<?php echo number_format($minimum_bid, 2); ?>):</label>
                <input type="number" id="bid_amount" name="bid_amount" step="0.01" min="<?php echo $minimum_bid; ?>" required>
                <button type="submit">Submit Bid</button>
            </form>
        </div>
    </div>

    <script>
        // Get modal element
        var modal = document.getElementById("bidModal");
        // Get open modal button
        var bidBtn = document.getElementById("bidButton");
        // Get close button
        var closeBtn = document.getElementsByClassName("close")[0];
        // Get exit bid button
        var exitBidBtn = document.getElementById("exitBidButton");

        // Listen for open click
        bidBtn.onclick = function() {
            modal.style.display = "block";
        }

        // Listen for close click
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Listen for exit bid click
        exitBidBtn.onclick = function() {
            window.location.href = "user_dashboard.php"; // Redirect to another page
        }

        // Listen for outside click
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
