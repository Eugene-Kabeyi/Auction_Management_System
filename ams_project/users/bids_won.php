<?php
include __DIR__ . ('/../header.php');

if (empty($_SESSION['user_id']) && $_SESSION['login_type'] !== 'user') {

    
    $_SESSION['error'] = "Please Login to access the page";
    header("Location:login.php");
}

include __DIR__ . ('/../config.php');

$stmt = $conn->prepare("
    SELECT 
        a.auction_id,
        a.bidder_id,
        a.result,
        c.auction_name,
        c.auction_code
    FROM auction_bids a
    JOIN users b ON a.bidder_id = b.UID
    JOIN auctions c ON a.auction_id = c.auction_id
    WHERE a.bidder_id = :user_id && a.result = :result
");

$stmt->execute([
    ':user_id' => $_SESSION['user_id'],
    'result' => "won"
]);

$results = $stmt->fetchAll();

?>

<head>
    <style>
        .outer_container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 80%;
            margin: 0 auto;
            justify-content: center;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
        <?php 
        if(empty($results)){
            echo htmlspecialchars("No Bids Won yet");
        }
        
        foreach ($results as $result): ?>
            <td><?= htmlspecialchars($result['auction_id']) ?></td>
            <td><?= htmlspecialchars($result['auction_name']) ?></td>
            <td><?= htmlspecialchars($result['auction_code']) ?></td>
          
        
        <?php endforeach ?>
    </table>
    </div>

</body>
<?php
include __DIR__.("/../footer.php");
?>