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
$stmt = mysqli_prepare($conn, "SELECT * FROM consigner_items");
mysqli_stmt_execute($stmt);
$items = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

// Display the items in a table 

?>

<head>
    <title>Consigner Items List</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
        
    </style>
</head>

<body>
    <h2 style="text-align: center;">Consigner Items List</h2>
    <!-- Back to  Dashboard -->
    <a href="staff_dashboard.php" style="margin:auto" class="back">
        Back to Dashboard
    </a>
     <a href="evaluated_list.php" style="margin:4px auto" class="back">
        To Evaluated Items
    </a>
    <!--Filter button-->
    <form method="GET" style="display:flex; flex-direction: row; background-color: transparent !important; border: none; ; margin:auto; max-width: fit-content; gap:10px;">
        
        <input type="hidden" name="filter" value="pending">
        
        <button type="submit" class="filter_button pending">Show Pending</button>
        <button type="submit" name="filter" value="all" class="filter_button show_all">Show All</button>
        
    </form>

   

    <?php
    //Filter functionality
    if (isset($_GET['filter']) && $_GET['filter'] === 'pending') {
        $stmt = mysqli_prepare($conn, "SELECT * FROM consigner_items WHERE item_status = 'pending'");
        mysqli_stmt_execute($stmt);
        $items = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
    } elseif (isset($_GET['filter']) && $_GET['filter'] === 'all') {
        $stmt = mysqli_prepare($conn, "SELECT * FROM consigner_items");
        mysqli_stmt_execute($stmt);
        $items = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
    }
    ?>
    <div class="outer_container">
    <div class="inner_container">
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
    </div>
    </div>
</body>
<?php include __DIR__ . '/../footer.php'; ?>