<?php
include __DIR__ . ('/../header.php');

if (empty($_SESSION['user_id']) && $_SESSION['login_type'] !== 'user') {


    $_SESSION['error'] = "Please Login to access the page";
    header("Location:login.php");
}

include __DIR__ . ('/../config.php');

$stmt = mysqli_prepare($conn, "
    SELECT 
        a.auction_id,
        a.bidder_id,
        a.amount_bidded,
        a.bid_status,
        a.result,
        c.auction_name,
        c.auction_code
    FROM auction_bids a
    JOIN users b ON a.bidder_id = b.UID
    JOIN auctions c ON a.auction_id = c.auction_id
    WHERE a.bidder_id = ?
");

mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$results = mysqli_fetch_all($results, MYSQLI_ASSOC);

?>

<head>
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
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 100%;
            margin: 0 40px;
            justify-content: center;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;

        }

        th,
        td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        td a {
            border-radius: 5px;
            color: #ffffff;
            text-decoration: none;
            background-color: #1f2933;
            padding: 6px 12px;
        }

        td a:hover {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #1f2933;
        }
         .back {
            display: inline-block;
           
            text-decoration:none;
            padding: 10px 15px;
            background-color: #2c2d2d;
            color: #fff;
            border-radius: 4px;
            margin: auto;
        }
        .back:hover {
            background-color: #ffffff;
            color: #2c2d2d;
        }
    </style>

</head>

<body style="background-color: #ebe9e9;">
    <h2>Bidding History</h2>
    <a href="user_dashboard.php" class="back">Back to Dashboard</a>
    <div class="outer_container">
        <table>
            <tr>
                <th>Auction Name</th>
                <th>Amount Bidded</th>
                <th>Bid Status</th>
                <th>Result</th>
            </tr>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?= htmlspecialchars($result['auction_name']) ?></td>
                    <td><?= htmlspecialchars($result['amount_bidded']) ?></td>
                    <td><?= htmlspecialchars($result['bid_status']) ?></td>
                    <td><?= htmlspecialchars($result['result']) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>

</body>
<?php
include __DIR__ . ("/../footer.php");
?>

</html>