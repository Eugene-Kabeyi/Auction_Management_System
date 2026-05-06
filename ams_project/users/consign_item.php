<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id']) && $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please log in to access this page";
    header("Location: login.php");
    exit;
}

include __DIR__ . '/../header.php'; ?>

<head>
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
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: center;
            margin: 20px auto;

        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="flash error">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h2 style="text-align: center">Consign an Item</h2>

    <div class="outer_form_container">
        <h4 style="text-align:center;">Please fill in the form below to consign an item.</h4>
        <br>
        <!--Form for entering items data-->
        <form action="" method="POST" enctype="multipart/form-data" class="form_data" onsubmit="return validateForm()">

            <label>Item Name</label>
            <input type="text" name="item_name" id="name" required>

            <label>Item Quantity</label>
            <input type="text" name="item_quantity" id="quantity" required>

            <label>Item Description</label>
            <textarea name="item_description" id="description"></textarea>

            <label>Item Category</label>
            <input type="text" name="item_category">

            <label>Item Condition</label>
            <select name="item_condition" id="condition" required>
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


<?php
require '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $consigner_id = $_SESSION['user_id'];

    $item_name = $_POST['item_name'];
    $item_quantity = $_POST['item_quantity'];
    $item_description = $_POST['item_description'];
    $item_category = $_POST['item_category'];
    $item_condition = $_POST['item_condition'];

    // Handle image upload
    $image_path = null;

    //Check if image file is uploaded
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/consigned_items/'; //Ensure directory exists

        //Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);//Create directory with permissions
        }
        $uploadFile = $uploadDir . basename($_FILES['item_image']['name']); //Set the upload file path
        //Move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['item_image']['tmp_name'], $uploadFile)) {
            $image_path = $uploadFile; //Store the image path
        } else {
            echo "❌ Image upload failed."; //Handle upload failure
        }
    }

    $sql = "INSERT INTO consigner_items 
        (item_name, item_quantity, item_description, item_category, item_condition, image_path, consigner_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([
        $item_name,
        $item_quantity,
        $item_description,
        $item_category,
        $item_condition,
        $image_path,
        $consigner_id
    ]);

    if ($success) {
        $_SESSION['success'] = "Item consigned successfully!";
        header("Location: user_dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: consign_item.php");
    }

}
?>