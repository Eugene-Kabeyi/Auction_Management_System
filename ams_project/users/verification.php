<?php include __DIR__ . '/../header.php'; ?>

<body>
    <h2 class="top_heading">User Verification</h2>
    <h1 class="user_name">Hello&nbsp;
        <?php if (isset($_SESSION['username'])) {
            echo $_SESSION['username'];
        } else {
            echo "User";
        } ?></h1>
    <!--Display profile photo here-->
    <div class="verify_container">
        <div class="profile_photo">
            <img src="../images/profile_placeholder.png" alt="Profile Photo" class="profile_img">
            <button class="change_image">Change Image</button>
        </div>
        <!--Form for personal details her and national id document submission-->
        <div>
            <form action="handle_verification" method="post">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="national_id">National ID:</label>
                <input type="text" id="national_id" name="national_id" required>

                <label for="national_id_doc">Upload National ID Document:</label>
                <input type="file" id="national_id_doc" name="national_id_doc" required>

                <button type="submit">Submit Verification</button>
            </form>
        </div>
    </div>
</body>