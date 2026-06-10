<?php
include __DIR__ . '/../header.php';

//Fetch auction list from the database
include __DIR__ . '/../config.php';

// GET FILTER VALUES FIRST
$type = $_GET['type'] ?? 'ongoing';
$range = $_GET['range'] ?? '';

//  BUILD QUERY
$query = "SELECT * FROM auctions ";

// UPCOMING / FUTURE
if ($type === 'upcoming' ) {
    $query .= " WHERE start_time > NOW()";
}

// ONGOING
elseif ($type === 'ongoing') {
    $query .= " WHERE start_time <= NOW() AND end_time >= NOW()";
}

// PAST
elseif ($type === 'past') {
    $query .= " WHERE end_time < NOW()";

    if ($range === 'week') {
        $query .= " AND end_time >= NOW() - INTERVAL 7 DAY";
    } elseif ($range === 'month') {
        $query .= " AND end_time >= NOW() - INTERVAL 1 MONTH";
    } elseif ($range === 'year') {
        $query .= " AND end_time >= NOW() - INTERVAL 1 YEAR";
    }
}

// ORDER
$query .= " ORDER BY start_time DESC";


$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$auctions = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<head>
    <title>Auction List</title>
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
            margin: 0px auto;
            overflow: auto;

        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
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

        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        form label {
            font-weight: bold;
            font-size: 20px;
        }

        form select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 6px 12px;
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #1f2933;
        }
    </style>
</head>

<body>
    <form method="GET" style="margin:20px;" class="form_class">
        <label>View:</label>

        <select name="type">
            <option value="upcoming" <?= ($type === 'upcoming') ? 'selected' : '' ?>>Upcoming</option>
            <option value="ongoing" <?= ($type === 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
            <option value="past" <?= ($type === 'past') ? 'selected' : '' ?>>Past</option>
        </select>

        <label>Range (for past):</label>
        <select name="range">
            <option value="">All</option>
            <option value="week" <?= ($range === 'week') ? 'selected' : '' ?>>Last 7 Days</option>
            <option value="month" <?= ($range === 'month') ? 'selected' : '' ?>>Last 30 Days</option>
            <option value="year" <?= ($range === 'year') ? 'selected' : '' ?>>Last 1 Year</option>
        </select>

        <button type="submit">Filter</button>
    </form>
    <h2 style="color: red;">
        <?php
        if ($type === 'past') {
            echo "Past Auctions";
        } elseif ($type === 'ongoing') {
            echo "Live Auctions";
        } else {
            echo "Upcoming Auctions";
        }
        ?>
    </h2>
    <h2>Auction List</h2>
    <div class="outer_container">
        <table>
            <thead>
                <tr>
                    <th>Auction ID</th>
                    <th>Auction Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <?php if(isset($_SESSION['user_id'])&& $type === "ongoing"):  ?>
                    <th>Action</th>
                    <?php endif;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auctions as $auction): ?>
                    <?php $start_time = date("F j, Y, g:i a", strtotime($auction['start_time'])); // Format start time for display
                        $end_time = date("F j, Y, g:i a", strtotime($auction['end_time'])); // Format end time for display ?>
                    <tr>
                        <td><?=  ($auction['auction_id']) ?></td>
                        <td><?=  ($auction['auction_name']) ?></td>
                        <td><?=  ($start_time) ?></td>
                        <td><?=  ($end_time) ?></td>
                        <?php if(isset($_SESSION['user_id']) && $type !== "upcoming"):  ?>
                        <td><a href="live_auction.php?auction_id=<?= $auction['auction_id'] ?>">View</a></td>
                        <?php endif;?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <?php include __DIR__ . '/../footer.php'; ?>