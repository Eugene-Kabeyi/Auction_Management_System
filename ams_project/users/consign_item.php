<?php
include 'header.php';
?>
<body>
     <h2>Consign an Item</h2>
    <!-- Form for consign_item.php goes here -->
     <p>Please fill in the form below to consign an item.</p>
     <form action="handle_consign_items.php" method="post">
         <label for="item_name">Item Name:</label>
         <input type="text" id="item_name" name="item_name" required>

         <label for="description">Description:</label>
         <textarea id="description" name="description"></textarea>

         <label for="quantity">Quantity:</label>
         <input type="number" id="quantity" name="quantity" required>

         <label for="reserved_price">Reserved Price:</label>
         <input type="number" id="reserved_price" name="reserved_price" required>

         <button type="submit">Consign Item</button>
     </form>

<?php
include 'footer.php';
?>
</body>
</html>