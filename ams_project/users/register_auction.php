<?php require __DIR__ . '/../header.php'; ?>
<main class="dashboard-container">
    <h1>Register for Auction</h2>
    <h2>Hello&nbsp;<?php if(isset($_SESSION['f_name'])) { echo htmlspecialchars($_SESSION['f_name']); } else { echo htmlspecialchars("User"); } ?></h1>
    <div class="outer_container">
        <div class="f_inner_container">
            <img src="../images/auction_registration.png" alt="Auction Registration" class="profile_img">
        </div>
        <div class="s_inner_container">
            <form action="consign_item.php" method="post">
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
</main>
<?php require __DIR__ . '/../footer.php'; ?>
<?php
// Handle_registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $auction_name = $_POST['auction_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Here you would typically insert the data into a database
    // For demonstration, we'll just echo the values
    echo "Auction Name: " . htmlspecialchars($auction_name) . "<br>";
    echo "Description: " . htmlspecialchars($description) . "<br>";
    echo "Start Date: " . htmlspecialchars($start_date) . "<br>";
    echo "End Date: " . htmlspecialchars($end_date) . "<br>";

    // Redirect or display a success message as needed
}