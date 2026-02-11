<?php 
//Fetch auction list from the database
include __DIR__ . '/../config.php';
$stmt = $conn->prepare("SELECT * FROM auctions WHERE status = 'upcoming';");
$stmt->execute();
$auctions = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../header.php'; ?>
<head>
    <title>Auction List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }   
        th, td {
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
    <h2>Auction List</h2>
    <table>
        <thead>
            <tr>
                <th>Auction ID</th>
                <th>Auction Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($auctions as $auction): ?>
            <?php $start_time = date("F j, Y, g:i a", strtotime($auction['start_time'])); // Format start time for display
            $end_time = date("F j, Y, g:i a", strtotime($auction['end_time'])); // Format end time for display ?>
                <tr>
                    <td><?= htmlspecialchars($auction['auction_id']) ?></td>
                    <td><?= htmlspecialchars($auction['auction_name']) ?></td>
                    <td><?= htmlspecialchars($start_time) ?></td>
                    <td><?= htmlspecialchars($end_time) ?></td>
                    <td><?= htmlspecialchars($auction['status']) ?></td>
                    <td><a href="live_auction.php?auction_id=<?= $auction['auction_id'] ?>">View Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add pagination or filtering options here if needed -->