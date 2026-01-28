<!-- header.php -->
 <?php //fetch session data
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['username'] = $_SESSION['username'] ?? '';
    ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/styles.css">
 </head>
<nav class="navbar">
    <div class="logo-container">
        <div class="logo">AMS</div>
        <div>
            <div>Auctioneer Management System</div>
            <div class="logo-subtitle">Streamline Your Auction Business</div>
        </div>
    </div>
    
    <div class="nav-links">
        <a href="#" class="nav-item">Dashboard</a>
        <a href="#" class="nav-item">Auctions</a>
        <a href="#" class="nav-item">Inventory</a>
        <a href="#" class="nav-item">Bidders</a>
        <a href="#" class="nav-item">Reports</a>
        <a href="#" class="nav-item">Settings</a>
    </div>
    
    <div class="login-container">
        <div id="userInfo" class="user-info" style="display: none;">
            <div class="user-avatar" id="userAvatar">JD</div>
            <span id="userName"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
        </div>
        <button id="loginBtn" class="login-btn"><a href="login.html">Login</a></button>
    </div>

    <script>
        //Check if user is logged in and update user info display

        // 
        document.addEventListener("DOMContentLoaded", function() {
            // Get username from PHP session
            var username = "<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>";
            // Update UI based on login status
            if (username) {
                document.getElementById("userInfo").style.display = "flex";
                document.getElementById("loginBtn").style.display = "none";
                document.getElementById("userAvatar").textContent = username.charAt(0).toUpperCase() + (username.charAt(1) ? username.charAt(1).toUpperCase() : '');
            } else {
                document.getElementById("userInfo").style.display = "none";
            }});
    </script>
</nav>