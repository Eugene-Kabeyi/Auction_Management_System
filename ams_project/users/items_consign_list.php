<?php
include __DIR__ . ('/../header.php');
if(empty($_SESSION['user_id']) && $_SESSION['login_type'] !== 'user'){
    $_SESSION['error'] = "Please login to access the page";
    header('Location:login.php');

}
include __DIR__. ('/../header.php');
$tmt = $conn->prepare('SELECT * FROM consigner_items WHERE consigner_id = :user_id ') ;
$tmt -> execute(["user_id" => $_SESSION['user_id']]);
$items = $tmt ->fetchAll()
?>
<head>
    <style>
        .outer_container{
            width: 80%;
            display: flex;
            flex-direction: column;
            gap :20px;

        }
        h2{
            text-align: center;

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
                            <img src="<?= htmlspecialchars($item['image_path']) ?>" 
                                 width="60" height="60" 
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
include __DIR__.("/../footer.php");
?>

