<?php 

include __DIR__ . '/../header.php';
?>
<body>
    <h2>Approve Item</h2>
    <!-- Form for approve_item.php goes here -->
     <div class="outer_container">
        <div class="f_inner_container"></div>
        <div class="s_inner_container">
    <p>Please review the item details below and approve or reject the item.</p>
    <form action="" method="post">
        <label for="item_id">Item ID:</label>
        <input type="text" id="item_id" name="item_id" required>

        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="reserved_price">Reserved Price:</label>
        <input type="number" id="reserved_price" name="reserved_price" required>

        <button type="submit" name="action" value="approve">Approve Item</button>
        <button type="submit" name="action" value="reject">Reject Item</button>
    </form>
    </div>
    </div>

    <script>
        // Make the form read-only for review purposes - later will apply DRY principle
        document.getElementById('item_id').readOnly = true;
        document.getElementById('item_name').readOnly = true;
        document.getElementById('description').readOnly = true;
        document.getElementById('quantity').readOnly = true;
        document.getElementById('reserved_price').readOnly = true;
    </script>
</body>
<?php include __DIR__ . '/../footer.php'; ?>
<?php
require '../config.php';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $action = $_POST['action']; 
    
    if ($action === 'approve') {
        // Update item status to approved in the database
        $stmt = $conn->prepare("UPDATE items SET status = 'approved' WHERE item_id = :item_id");
        $stmt->execute([':item_id' => $item_id]);
        echo "✅ Item approved successfully!";
    } elseif ($action === 'reject') {
        // Update item status to rejected in the database
        $stmt = $conn->prepare("UPDATE items SET status = 'rejected' WHERE item_id = :item_id");
        $stmt->execute([':item_id' => $item_id]);
        echo "❌ Item rejected.";
    }
}
?>  
    
