<?php include __DIR__ . '../header.php'; ?>
<body>
    <h2>Register for Auction</h2>
    <h1>Hello&nbsp; <?php if(isset($_SESSION['username'])) { echo $_SESSION['username']; } else { echo "User"; } ?></h1>
    <div class="outer_container">
        <div class="f_inner_container"></div>
        <div class="s_inner_container">
            <form action="handle_registration" method="post">
                <label for="auction_name">Auction Name:</label>
                <input type="text" id="auction_name" name="auction_name" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>

                <button type="submit">Register Auction</button>
            </form>
        </div>
    </div>