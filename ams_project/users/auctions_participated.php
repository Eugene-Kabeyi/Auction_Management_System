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
        a.result,
        c.auction_name,
        c.auction_code
    FROM auction_bids a
    JOIN users b ON a.bidder_id = b.UID
    JOIN auctions c ON a.auction_id = c.auction_id
    WHERE a.bidder_id = ?
    GROUP BY auction_name;
");

mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$auctions = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: center;
            margin: 20px auto;

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
    </style>

</head>

<body>
    <h2>Auctions Participated</h2>
    <div class="outer_container">
        <table>
            <tr>
                <th>Auction ID</th>
                <th>Auction Name</th>
                <th>Auction Code</th>

            </tr>
            <?php foreach ($auctions as $auction): ?>
                <tr>
                    <td><?=  ($auction['auction_id']) ?></td>
                    <td><?=  ($auction['auction_name']) ?></td>
                    <td><?=  ($auction['auction_code']) ?></td>
                </tr>

            <?php endforeach ?>
        </table>
    </div>

</body>
<?php
include __DIR__ . ("/../footer.php");
?>