<?php include 'header.php'; ?>
<head>
    <title>Auction Management System</title>
    <style>
        /* GENERAL PAGE */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f3f4f6;
    color: #333;
}

/* HERO SECTION */
h1 {
    text-align: center;
    margin-top: 60px;
    font-size: 42px;
}

p {
    text-align: center;
    font-size: 18px;
    margin: 10px auto;
    max-width: 600px;
}

/* BUTTONS */
.hero-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 20px 0 50px;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.primary {
    background: #1f2933;
    color: white;
}

.primary:hover {
    background: #111827;
}

.secondary {
    background: white;
    color: #1f2933;
    border: 2px solid #1f2933;
}

.secondary:hover {
    background: #1f2933;
    color: white;
}

/* ABOUT SECTION */
.about-section {
    background: white;
    padding: 40px;
    margin: 20px auto;
    max-width: 900px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.about-section h2 {
    text-align: center;
    margin-bottom: 15px;
}

/* FEATURES */
.features-section {
    padding: 40px;
    text-align: center;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
}

.feature-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.feature-card:hover {
    transform: translateY(-5px);
}

/* CALL TO ACTION */
.cta-section {
    background: #1f2933;
    color: white;
    text-align: center;
    padding: 50px 20px;
    margin-top: 40px 0px;
}

.cta-section .btn {
    margin: 15px 0px ;
    border: 1px solid white;
}
.cta-section .btn:hover {
    background: white;
    color: #1f2933;
}
</style>
</head>
<section class="hero">
    <h1>Welcome to AMS</h1>
<p>Manage auctions, users, staff, and payments easily in one system.</p>

<div class="hero-buttons">
    <a href="login.php" class="btn secondary">Login</a>
    <a href="users/register.php" class="btn primary">Create Account</a>
</div>
</section>

<!-- ABOUT SECTION -->
<section class="about-section">
    <h2>About AMS</h2>
    <p>
        The Auction Management System (AMS) is a web-based platform designed to simplify
        the process of managing auctions. It allows administrators to manage items,
        staff, and users while giving bidders a smooth experience when participating
        in auctions.
    </p>
</section>

<!-- FEATURES SECTION -->
<section class="features-section">
    <h2>Key Features</h2>

    <div class="features-grid">
        <div class="feature-card">
            <h3>User Management</h3>
            <p>Register, login, and manage user accounts securely.</p>
        </div>

        <div class="feature-card">
            <h3>Auction Tracking</h3>
            <p>Track live, upcoming, and completed auctions in real-time.</p>
        </div>

        <div class="feature-card">
            <h3>Payment Processing</h3>
            <p>Secure payment processing with invoice generation.</p>
        </div>

        <div class="feature-card">
            <h3>Admin Control</h3>
            <p>Full control over staff and system data.</p>
        </div>
    </div>
</section>

<!-- CALL TO ACTION -->
<section class="cta-section">
    <h2>Ready to start bidding?</h2>
    <p>Join AMS today and explore live auctions.</p>
    <br>
    <a href="users/register.php" class="btn primary">Create Account</a>
</section>

<?php include 'footer.php'; ?>
