<?php include 'config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $department = $_POST['department'];

    $errors = [];

    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if (empty($phone)) $errors[] = "Phone number is required.";
    if (empty($department)) $errors[] = "Department is required.";

    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        $check->close();
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, department) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $hashed_password, $phone, $department);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now login.";
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Department Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">Department Portal</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="login.php" class="btn-login">Login</a></li>
            <li><a href="register.php" class="btn-register active">Register</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>Registration Form</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="registerForm">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter your full name"
                       value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password (min 6 chars)" required>
                <small id="passwordError" class="field-error"></small>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                       value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">-- Select Department --</option>
                    <option value="Computer Science" <?php echo (isset($department) && $department == "Computer Science") ? 'selected' : ''; ?>>Computer Science</option>
                    <option value="Engineering" <?php echo (isset($department) && $department == "Engineering") ? 'selected' : ''; ?>>Engineering</option>
                    <option value="Business" <?php echo (isset($department) && $department == "Business") ? 'selected' : ''; ?>>Business</option>
                    <option value="Arts" <?php echo (isset($department) && $department == "Arts") ? 'selected' : ''; ?>>Arts</option>
                    <option value="Science" <?php echo (isset($department) && $department == "Science") ? 'selected' : ''; ?>>Science</option>
                    <option value="Mathematics" <?php echo (isset($department) && $department == "Mathematics") ? 'selected' : ''; ?>>Mathematics</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Register</button>
        </form>

        <p class="form-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            var password = document.getElementById('password').value;
            var errorEl = document.getElementById('passwordError');
            if (password.length < 6) {
                e.preventDefault();
                errorEl.textContent = 'Password must be at least 6 characters.';
            } else {
                errorEl.textContent = '';
            }
        });
    </script>

    <footer>
        <p>&copy; 2026 Department Portal. All rights reserved.</p>
    </footer>
</body>
</html>
