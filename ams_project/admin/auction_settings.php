<?php
include __DIR__ . '/../header.php';
include __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'admin') {
    header('Location: ../staff/staff_login.php');
    session_destroy();
    $_SESSION['error'] = "Please log in as an admin to access this page.";
    exit();
}
//fetch auction settings
$stmt = mysqli_prepare($conn, "SELECT * FROM auction_settings");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$settings = mysqli_fetch_assoc($result);
?>
<head>
    <title>Auction Settings</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>
<body>
    <div class="outer_container f_container">
        <h2>Auction Settings</h2>
        <a href="admin_dashboard.php" style="margin:auto" class="back">
            Back to Dashboard
        </a>
        <!-- form action to update settings -->
        <form action="update_auction_settings.php" method="post">
            <label for="tax_rate">Tax Rate (%)</label>
            <input type="text" name="tax_rate" id="tax_rate" value="<?= $settings['tax_rate'] ?>" required>

            <label for="commission_rate">Commission Rate (%)</label>
            <input type="text" name="commission_rate" id="commission_rate" value="<?= $settings['commission_rate'] ?>" required>

           

            <button type="submit">Update Settings</button>        

    </div>
    </body>
    </html>
    <?php include __DIR__ . '/../footer.php'; ?>
    <script>
        // Ensure values are valid numbers before submitting
        document.querySelector('form').addEventListener('submit', function(e) {
            const taxRate = parseFloat(document.getElementById('tax_rate').value);
            const auctionDuration = parseInt(document.getElementById('auction_duration').value);

            if (isNaN(taxRate) || taxRate < 0) {
                e.preventDefault();
                alert('Please enter a valid tax rate.');
            }

            if (isNaN(auctionDuration) || auctionDuration <= 0) {
                e.preventDefault();
                alert('Please enter a valid auction duration.');
            }
        });
    </script>