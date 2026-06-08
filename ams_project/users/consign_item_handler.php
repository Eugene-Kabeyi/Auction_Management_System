<?php
require '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['login_type'] !== 'user') {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: login.php");
    session_destroy();
    exit();
}
include __DIR__ . '/../log_activity.php';

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
        $filename = time() . '_' . basename($_FILES['item_image']['name']);
        $uploadFile = $uploadDir . $filename; //Set the upload file path
        //Move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['item_image']['tmp_name'], $uploadFile)) {
            $image_path = $uploadFile; //Store the image path
            logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Uploaded image for consigned item: " . $item_name);
        } else {
            echo "Image upload failed."; //Handle upload failure
            logActivity($conn, $_SESSION['user_id'] ?? null, $_SESSION['username'] ?? 'Unknown', "Failed to upload image for consigned item.");
        }
    }

    $sql = "INSERT INTO consigner_items 
        (item_name, item_quantity, item_description, item_category, item_condition, image_path, consigner_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", 
        $item_name,
        $item_quantity,
        $item_description,
        $item_category,
        $item_condition,
        $image_path,
        $consigner_id
    );
    $success = mysqli_stmt_execute($stmt);


    if ($success) {
        $_SESSION['success'] = "Item consigned successfully!";
        logActivity($conn, $_SESSION['user_id'], $_SESSION['username'], "Consigned item: " . $item_name);
        header("Location: user_dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        logActivity($conn, $_SESSION['user_id'] ?? null, $_SESSION['username'] ?? 'Unknown', "Failed to consign item.");
        header("Location: consign_item.php");
    }

}
?>