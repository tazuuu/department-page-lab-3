<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Department Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">Department Portal</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" class="active">About Us</a></li>
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

    <div class="about-container">
        <h1>About Us</h1>

        <div class="about-section">
            <h2>Our Mission</h2>
            <p>We are dedicated to providing a centralized platform for managing departmental activities, resources, and communication. Our portal serves as a bridge between students, faculty, and administration.</p>
        </div>

        <div class="about-section">
            <h2>Our Departments</h2>
            <div class="dept-grid">
                <div class="dept-card">
                    <h3>Computer Science</h3>
                    <p>Advancing knowledge in computing, software development, and artificial intelligence.</p>
                </div>
                <div class="dept-card">
                    <h3>Engineering</h3>
                    <p>Innovating solutions through mechanical, civil, and electrical engineering disciplines.</p>
                </div>
                <div class="dept-card">
                    <h3>Business</h3>
                    <p>Developing future leaders with expertise in management, finance, and marketing.</p>
                </div>
                <div class="dept-card">
                    <h3>Arts</h3>
                    <p>Fostering creativity and cultural understanding through diverse artistic programs.</p>
                </div>
                <div class="dept-card">
                    <h3>Science</h3>
                    <p>Exploring the natural world through research in biology, chemistry, and physics.</p>
                </div>
                <div class="dept-card">
                    <h3>Mathematics</h3>
                    <p>Building strong foundations in pure and applied mathematical sciences.</p>
                </div>
            </div>
        </div>

        <div class="about-section">
            <h2>Contact Us</h2>
            <p>Email: info@departmentportal.com</p>
            <p>Phone: +1 (555) 123-4567</p>
            <p>Address: 123 University Ave, Academic City</p>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Department Portal. All rights reserved.</p>
    </footer>
</body>
</html>
