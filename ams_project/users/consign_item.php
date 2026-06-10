<?php
require  '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id']) && $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please log in to access this page";
    header("Location: login.php");
    exit;
}

include __DIR__ . '/../header.php'; 
$sql = "SELECT * FROM item_categories";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);

$categories = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

?>


<head>
    <title>Consign Item</title>
    <link rel="stylesheet" href="../css/form_table_styles.css">
</head>

<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h2 style="text-align: center">Consign an Item</h2>

    <div class="outer_form_container">

        <!--Form for entering items data-->
        <form action="consign_item_handler.php" method="POST" enctype="multipart/form-data" class="form_data" onsubmit="return validateForm()">
            <h4 style="text-align:center;">Please fill in the form below to consign an item.</h4>


            <label>Item Name</label>
            <input type="text" name="item_name" id="name" >

            <label>Item Quantity</label>
            <input type="text" name="item_quantity" id="quantity" >

            <label>Item Description</label>
            <textarea name="item_description" id="description"></textarea>

            <label>Item Category</label>
            <select type="text" name="item_category">
                <?php
                if (count($categories) > 0) {
                        foreach ($categories as $row) {
                            echo "<option value='{$row['category_name']}'>{$row['category_name']}</option>";
                        }
                    } else {
                        echo "<option value=''>No items available</option>";
                    }

                    ?>
            </select>

            <label>Item Condition</label>
            <select name="item_condition" id="condition" >
                <option value="">-- Select Condition --</option>
                <option value="new">New</option>
                <option value="used">Used</option>
                <option value="refurbished">Refurbished</option>
                <option value="antique">Antique</option>
            </select>

            <label>Item Image</label>
            <input type="file" name="item_image" accept="image/*">

            <button type="submit">Consign Item</button>

        </form>
    </div>

</body>
<script>
    // Main function (like your template style)
    function validateForm() {

        // Call all validations
        if (!validateName()) return false;
        if (!validateQuantity()) return false;
        if (!validateCondition()) return false;
        if (!validateImage()) return false;

        return true; // allow submission if all pass
    }

    //  Validate Item Name
    function validateName() {
        var name = document.getElementById("name").value;

        if (name.length == 0) {
            alert("You must enter an Item Name");
            document.getElementById("name").focus();
            return false;
        }
        return true;
    }

    //  Validate Quantity
    function validateQuantity() {
        var quantity = document.getElementById("quantity").value;

        if (quantity.length == 0 || isNaN(quantity)) {
            alert("Quantity must be a valid number");
            document.getElementById("quantity").focus();
            return false;
        }

        if (quantity <= 0) {
            alert("Quantity must be greater than 0");
            document.getElementById("quantity").focus();
            return false;
        }

        return true;
    }

    // Validate Condition (select box like your gender function)
    function validateCondition() {
        var index = document.getElementById("condition").selectedIndex;

        if (index == 0) {
            alert("Please select item condition");
            return false;
        }

        return true;
    }

    //  Validate Image Upload
    function validateImage() {
        var image = document.querySelector("input[name='item_image']").value;

        if (image.length == 0) {
            alert("Please upload an item image");
            return false;
        }

        return true;
    }

</script>
<?php include __DIR__ . '/../footer.php'; ?>