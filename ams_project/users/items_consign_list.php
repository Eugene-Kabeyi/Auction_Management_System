<?php
include __DIR__ . ('/../header.php');
if (empty($_SESSION['user_id']) && $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please login to access the page";
    header('Location:login.php');

}
include __DIR__ . ('/../config.php');
$tmt = $conn->prepare('SELECT * FROM consigner_items WHERE consigner_id = :user_id ');
$tmt->execute(["user_id" => $_SESSION['user_id']]);
$items = $tmt->fetchAll()
    ?>

<head>
    <title>My Consigned Items</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>

    <h2>My Consigned Items</h2>

    <div class="outer_container">
        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Category</th>
                <th>Condition</th>
                <th>Status</th>
                <th>Created</th>
            </tr>

            <?php
            if (empty($items)) {
                echo "<tr><td colspan='8'>No items submitted yet.</td></tr>";
            }

            foreach ($items as $item):
                $statusClass = "status-" . htmlspecialchars($item['item_status']);
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['item_id']) ?></td>

                    <td>
                        <?php if (!empty($item['image_path'])): ?>
                            <img src="<?= htmlspecialchars($item['image_path']) ?>" width="60" height="60"
                                style="object-fit:cover; border-radius:5px;">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td><?= htmlspecialchars($item['item_quantity']) ?></td>
                    <td><?= htmlspecialchars($item['item_category'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['item_condition']) ?></td>
                    <td><?= htmlspecialchars($item['item_status']) ?></td>
                    <td><?= htmlspecialchars($item['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
<?php
include __DIR__ . ("/../footer.php");
?>