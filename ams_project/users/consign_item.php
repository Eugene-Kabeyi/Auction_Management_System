<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $_SESSION['user_id'] ?? '';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include __DIR__ . '/../header.php'; ?>

<main>
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
        <form action="" method="POST" enctype="multipart/form-data" class="form_data">

            <label>Item Name</label>
            <input type="text" name="item_name" required>

            <label>Item Quantity</label>
            <input type="number" name="item_quantity" min="1" required>

            <label>Item Description</label>
            <textarea name="item_description"></textarea>

            <label>Item Category</label>
            <input type="text" name="item_category">

            <label>Item Condition</label>
            <select name="item_condition" required>
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

</main>
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