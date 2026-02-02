<?php include __DIR__ . '/../header.php'; ?>
<body>
    <h2>User Profile</h2>
    <!-- User profile details go here -->
    <p>Hello &nbsp;
        <?php if (isset($_SESSION['username'])) {
            echo htmlspecialchars($_SESSION['username']);
        } else {
            echo htmlspecialchars("User");
        } ?>
    </p>
    <div class="outer_container">
        <div class="f_inner_container">
            <img src="<?php echo isset($_SESSION['image_path']) ? $_SESSION['image_path'] : '../images/profile_placeholder.png'; ?>" alt="Profile Photo" class="profile_img">
        </div>
        <div class="s_inner_container">
        <!--Form for dispalying user details with edit button and submit button -->
            <form action="handle_profile_update.php" method="post">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo isset($_SESSION['f_name']) ? htmlspecialchars($_SESSION['f_name']) : ''; ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo isset($_SESSION['l_name']) ? htmlspecialchars($_SESSION['l_name']) : ''; ?>" required>

                <button type="submit">Update Profile</button>
            </form>
        </div>


<?php
$image_path = null;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/profile_photos/';
                $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                    $image_path = $uploadFile;
                } else {
                    echo "❌ Image upload failed.";
                }
            }
?>