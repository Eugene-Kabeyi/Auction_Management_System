<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Sign Up</title>
    <link rel="icon" type="image/png" href="../uploads/favicon.png">

    <style>
        /* Page background */
        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background-color: #f3f4f6;
            font-size: 14px;
            color: #374151;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .register_container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-items: center;
            gap: 20px;
            width: 40%;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        /* Logo */
        .logo {
            display: block;
            width: 48px;
            height: 48px;
            margin: 0 auto 10px;
            background-color: #1f2933;
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            border-radius: 6px;
            text-align: center;
            line-height: 48px;
        }

        /* Headings */
        .register_container h2 {
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .register_container h3 {
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 5px;
        }

        /* Form */
        form {
            max-width: 95%;
        }

        /* Labels */
        label {
            display: block;
            margin-top: 14px;
            font-weight: 500;
            font-size: 15px;
            color: #374151;
        }

        /* Inputs & select */
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        select {
            margin-left: 13px !important;
        }

        input:focus,
        select:focus {
            border-color: #1f2933;
            outline: none;
        }

        /* Submit button */
        input[type="submit"] {
            width: 100%;
            margin-top: 22px;
            padding: 12px;
            background-color: #1f2933;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #374151;
        }

        /* Error messages */
        .error {
            display: block;
            font-size: 12px;
            color: #dc2626;
            margin-top: 4px;
        }

        /* Left decorative panel */
        .leftdecorate {
            position: absolute;
            top: 0;
            left: 0;
            width: 320px;
            height: 100vh;
            background-color: #1f2933;
        }

        /* Make sure form stays visible */

        /* Password text to be disc */
        .pass_w {
            -webkit-text-security: disc;
            font-size: 16px;
            letter-spacing: 2px;
        }

        /* Hide/Show Password inside the password field */
        .hide_show {
            position: relative;
            top: -32px;
            left: 140px;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }

        /* Already have account link */
        .already {
            display: block;
            margin-top: 16px;
            font-size: 13px;
            color: #6b7280;
            text-align: center;
        }

        .already a {
            color: #1f2933;
            text-decoration: none;
            font-weight: 500;
        }

        .already a:hover {
            text-decoration: underline;
        }

        .flash {
            position: fixed;
            top: 20px;
            left: 20px;
            min-width: 260px;
            padding: 14px 18px;
            border-radius: 6px;
            font-size: 14px;
            z-index: 9999;
            animation: slideIn 0.4s ease, fadeOut 0.4s ease 4s forwards;
        }

        .flash.success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 5px solid #10b981;
        }

        .flash.error {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 5px solid #ef4444;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(-30px);
            }
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background-color: #1f2933;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .back:hover {
            background-color: white;
            color: #1f2933;
            border: 2px solid #1f2933;
        }
    </style>
</head>

<body>
    <!-- back button to user_dashboard.php -->

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="register_container">
        <a href="../index.php" class="back">Back to Home</a>
        <h2>Register for AMS</h2>
        <span class="logo">AMS</span>
        <form action="../handle_register.php" method="post" onsubmit="return validateForm()">
            <label>National ID</label>
            <input type="text" id="national_id" name="national_id">

            <label>Business ID (Optional)</label>
            <input type="text" name="business_id">

            <label>First Name</label>
            <input type="text" id="firstname" name="firstname">

            <label>Second Name (Optional)</label>
            <input type="text" name="secondname">

            <label>Surname</label>
            <input type="text" id="surname" name="surname">

            <label>Business Name (Optional)</label>
            <input type="text" name="business_name">

            <label>Phone Number</label>
            <input type="text" id="phone" name="phone_number">

            <label>Email</label>
            <input type="text" id="email" name="email">


            <label>Username</label>
            <input type="text" id="username" name="username">


            <label>Password</label>
            <input type="text" id="password" name="password" class="pass_w">
            <span class="hide_show" id="togglePassword">Hide/Show</span>


            <label>Role</label>
            <select id="role_id" name="role_id">
                <option value="">-- Select Role --</option>
                <option value="1">Consigner</option>
                <option value="2">Bidder</option>

            </select>


            <input type="submit" value="Register">
        </form>
        <span class="already">Already have an account? <a href="login.php">Login</a></span>
        <script>

            // Toggle password visibility
            function togglePasswordVisibility() {

                // Fetch the password field and the toggle text element
                var passwordField = document.getElementById("password");
                var toggleText = document.getElementById("togglePassword");

                // Toggle the class to switch between text and password styles
                if (passwordField.classList.contains("pass_w")) {
                    passwordField.classList.remove("pass_w");
                    toggleText.textContent = "Hide";
                } else {
                    passwordField.classList.add("pass_w");
                    toggleText.textContent = "Show";
                }
            }
            document.getElementById("togglePassword").addEventListener("click", togglePasswordVisibility);

            function validateForm() {
                var national_id = document.getElementById("national_id").value.trim();
                var role_id = document.getElementById("role_id").value;
                var firstname = document.getElementById("firstname").value.trim();
                var surname = document.getElementById("surname").value.trim();
                var phone = document.getElementById("phone").value.trim();
                var email = document.getElementById("email").value.trim();
                var username = document.getElementById("username").value.trim();
                var password = document.getElementById("password").value;

                if (national_id === "") {
                    alert("National ID required");
                    return false;
                }

                if (role_id === "") {
                    alert("Select a role");
                    return false;
                }

                if (firstname === "") {
                    alert("First name required");
                    return false;
                }

                if (surname === "") {
                    alert("Surname required");
                    return false;
                }

                if (phone === "") {
                    alert("Phone number required");
                    return false;
                }

                if (email === "" || email.indexOf("@") === -1) {
                    alert("Valid email required");
                    return false;
                }

                if (username.length < 4) {
                    alert("Username must be at least 4 characters");
                    return false;
                }

                if (password.length < 6) {
                    alert("Password must be at least 6 characters");
                    return false;
                }

                return true;
            }
        </script>

</body>

</html>