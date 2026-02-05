<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: ../staff/staff_login.php');
    exit();
}
//header
include __DIR__ . '/../header.php';
// Create a connection to the database
require_once __DIR__ . '/../config.php';

// Fetch all consigner items from the database
$stmt = $conn->prepare("SELECT * FROM consigner_items");
$stmt->execute();
$items = $stmt->fetchAll();

// Display the items in a table 

?>

<head>
    <title>Consigner Items List</title>
    <style>
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

        .filter_button {
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .filter_button:hover {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #1f2933;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Consigner Items List</h2>
    <!--Filter button-->
    <form method="GET" style="display:inline;">
        <input type="hidden" name="filter" value="pending">
        <button type="submit" class="filter_button pending">Show Pending</button>
        <button type="submit" name="filter" value="all" class="filter_button show_all">Show All</button>
    </form>

    <script>
        // Toggle filter buttons visibility
        const pendingButton = document.querySelector('.pending');
        const allButton = document.querySelector('.show_all');

        pendingButton.addEventListener('click', () => {
            pendingButton.classList.add('hidden');
            allButton.classList.remove('hidden');
        });

        allButton.addEventListener('click', () => {
            allButton.classList.add('hidden');
            pendingButton.classList.remove('hidden');
        });

    </script>

    <?php
    //Filter functionality
    if (isset($_GET['filter']) && $_GET['filter'] === 'pending') {
        $stmt = $conn->prepare("SELECT * FROM consigner_items WHERE item_status = 'pending'");
        $stmt->execute();
        $items = $stmt->fetchAll();
    } elseif (isset($_GET['filter']) && $_GET['filter'] === 'all') {
        $tmt = $conn->prepare("SELECT * FROM consigner_items");
        $stmt->execute();
        $items = $stmt->fetchAll();
    }
    ?>

    <table border="1">
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Condition</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($items as $item) { ?>


            <tr>
                <td><?php echo htmlspecialchars($item['item_id']); ?></td>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td><?php echo htmlspecialchars($item['item_description']); ?></td>
                <td><?php echo htmlspecialchars($item['item_category']); ?></td>
                <td><?php echo htmlspecialchars($item['item_condition']); ?></td>
                <td><?php echo htmlspecialchars($item['item_status']); ?></td>
                <td>
                    <?php if ($item['item_status'] === 'approved'): ?>
                        <span>No action needed</span>
                    <?php else: ?>
                        <a href="approve_item.php?item_id=<?= $item['item_id']; ?>">
                            Approve / Reject
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
<?php include __DIR__ . '/../footer.php'; ?>