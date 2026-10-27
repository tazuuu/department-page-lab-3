<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Portal - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">Department Portal</div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><span class="nav-user">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span></li>
                <li><a href="logout.php" class="btn-logout">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn-login">Login</a></li>
                <li><a href="register.php" class="btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="hero">
        <h1>Welcome to the Department Portal</h1>
        <p>Your gateway to departmental resources and information</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary">Get Started</a>
                <a href="login.php" class="btn btn-secondary">Login</a>
            </div>
        <?php else: ?>
            <p class="welcome-msg">You are logged in as <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></p>
        <?php endif; ?>
    </div>

    <div class="features">
        <div class="feature-card">
            <h3>Registration</h3>
            <p>Join our department by filling out a simple registration form with your details.</p>
        </div>
        <div class="feature-card">
            <h3>Department Info</h3>
            <p>Access information about various departments and their offerings.</p>
        </div>
        <div class="feature-card">
            <h3>Secure Access</h3>
            <p>Your data is protected with secure password hashing and session management.</p>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Department Portal. All rights reserved.</p>
    </footer>
</body>
</html>
