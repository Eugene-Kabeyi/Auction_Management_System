<!-- header.php -->
<?php //fetch session data
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="/ams_project/css/styles.css">
    <!--favicon-->
    <link rel="icon" type="image/png" href="../uploads/favicon.png">
    <link rel="icon" type="image/png" href="uploads/favicon.png">
</head>
<nav class="navbar">
    <div class="logo-container">
        <div class="logo">AMS</div>
        <div>
            <div>Auctioneer Management System</div>
            <div class="logo-subtitle">Streamline Your Auction Business</div>
        </div>
    </div>
    <div class="menu-toggle" id="menuToggle">
        ☰
    </div>

    <div class="nav-links" id="navLinks">
        <?php
        $link = '';
        $link2 = '../users/auction_list.php';
        if ($_SESSION['login_type'] === 'staff') {
            $link = '../ams_project/staff/staff_dashboard.php';
            $link2 = '../ams_project/staff/list_items.php';
        } elseif ($_SESSION['login_type'] === 'user') {
            $link = '../ams_project/users/user_dashboard.php';
        } else if ($_SESSION['login_type'] === 'admin') {
            $link = '../ams_project/admin/admin_dashboard.php';

        } else {
            $link = '../ams_project/users/login.php';
            $link2 = '../ams_project/users/auction_list.php';
        }
        ?>
        <!-- Home button to index.php -->
        <a href="../ams_project/index.php" class="nav-item">Home</a>
        <a href="<?php echo htmlspecialchars($link); ?>" class="nav-item">Dashboard</a>
        <a href="<?php echo htmlspecialchars($link2); ?>" class="nav-item">Auctions</a>
        <!-- <a href="#" class="nav-item">Bidders</a>
        <a href="#" class="nav-item">Reports</a>
        <a href="#" class="nav-item">Settings</a> -->
    </div>

    <div class="login-container">
        <div id="userInfo" class="user-info" style="display: none;">
            <div class="user-avatar" id="userAvatar">JD</div>
            <span id="userName"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>

            <span class="dropdown-arrow" id="dropdownArrow">▼</span>

            <div id="userDropdown" class="dropdown-menu">
                <a href="/../ams_project/profile.php">Profile</a>
                <a href="/../ams_project/logout.php">Logout</a>
            </div>
        </div>
        <!--Login by -->
        <button id="loginBtn" class="login-btn"><a href="login.html">Login</a></button>
    </div>

    <script>
        // DFisplay Logout and profile

        const userInfo = document.getElementById("userInfo");
        const dropdown = document.getElementById("userDropdown");
        const arrow = document.getElementById("dropdownArrow");

        userInfo.addEventListener("click", function (e) {
            const isOpen = dropdown.style.display === "block";

            dropdown.style.display = isOpen ? "none" : "block";
            arrow.classList.toggle("rotate");
        });
        
        document.addEventListener("click", function (e) {
            if (!userInfo.contains(e.target)) {
                dropdown.style.display = "none";
                arrow.classList.remove("rotate");
            }
        });
        //Check if user is logged in and update user info display

        // 
        document.addEventListener("DOMContentLoaded", function () {
            // Get username from PHP session
            var username = "<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>";
            // Update UI based on login status
            if (username) {
                document.getElementById("userInfo").style.display = "flex";
                document.getElementById("loginBtn").style.display = "none";
                document.getElementById("userAvatar").textContent = username.charAt(0).toUpperCase() + (username.charAt(1) ? username.charAt(1).toUpperCase() : '');
            } else {
                document.getElementById("userInfo").style.display = "none";
            }
        });


        const menuToggle = document.getElementById("menuToggle");
        const navLinks = document.getElementById("navLinks");

        menuToggle.addEventListener("click", function () {

            navLinks.classList.toggle("active");

        });



    </script>
</nav>



