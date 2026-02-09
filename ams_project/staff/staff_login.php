<?php
session_start();
$_SESSION['error'] = $_SESSION['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login </title>

    <style>
        /* Body */
        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #374151;
        }

        /* Login container */
        .login_container {
            position: relative;
            width: 420px;
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
            margin: 12px auto;
            background-color: #1f2933;
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            border-radius: 6px;
            line-height: 48px;
            text-align: center;
        }

        /* Headings */
        .login_container h2 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .login_container h3 {
            font-size: 15px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* Form */
        .login_form {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        /* Labels */
        .login_form label {
            font-size: 13px;
            font-weight: 500;
            margin-top: 14px;
            color: #374151;
        }

        /* Inputs */
        .login_form input[type="text"],
        .login_form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        .login_form input:focus {
            border-color: #1f2933;
            outline: none;
        }

        .login_form select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
            background-color: #ffffff;
            color: #374151;
        }

        /* Submit button */
        .login_form button {
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
            transition: background-color 0.4s ease;
        }

        .login_form button:hover {
            background-color: #ffffff;
            color: #1f2933;
            border: 2px solid #1f2933;
        }

        .hide_show {
            position: absolute;
            right: 30px;
            top: 305px;
            cursor: pointer;
            font-size: 12px;
            color: #1f2933;
            user-select: none;
        }

        .pass_w {
            font-family: Arial, sans-serif;
            -webkit-text-security: disc;
            letter-spacing: 2px;

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

        /* Flash message styles */
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
    </style>
</head>

<body>


    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash error">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <div class="login_container">
        <!-- Logo and Headings -->
        <h2>Welcome to AMS</h2>
        <span class="logo">AMS</span>
        <h3>Staff Log In</h3>
        <!-- Login Form -->
        <form action="../handle_login.php" method="post" class="login_form">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <span class="hide_show" id="togglePassword">Hide/Show</span>
            <input type="text" id="password" name="password" class="pass_w" required>

            <!-- Choose between Admin and Staff -->
            <label for="login_type">Account Type:</label>
            <select id="login_type" name="login_type" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>

            <button type="submit">Log In</button>
        </form>
    </div>
    <script>
        // Validation of password field to ensure it is not empty and handle show/hide password
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            toggleButton.addEventListener('click', function () {
                if (passwordInput.classList.contains('pass_w')) {
                    passwordInput.classList.remove('pass_w');
                    toggleButton.textContent = "Hide";
                } else {
                    passwordInput.classList.add('pass_w');
                    toggleButton.textContent = "Show";
                }
            });
        }
        togglePasswordVisibility();

    </script>

</body>

</html>