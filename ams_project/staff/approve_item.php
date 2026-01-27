<?php 
include 'header.php';
?>
<body>
    <h2>Approve Item</h2>
    <!-- Form for approve_item.php goes here -->
    <p>Please review the item details below and approve or reject the item.</p>
    <form action="handle_approve_item.php" method="post">
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

    <script>
        // Make the form read-only for review purposes - later will apply DRY principle
        document.getElementById('item_id').readOnly = true;
        document.getElementById('item_name').readOnly = true;
        document.getElementById('description').readOnly = true;
        document.getElementById('quantity').readOnly = true;
        document.getElementById('reserved_price').readOnly = true;
    </script>
</body>