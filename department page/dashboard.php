<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT department, phone, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$user_dept = $user['department'];

$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_depts = $conn->query("SELECT COUNT(*) as count FROM departments")->fetch_assoc()['count'];

$dept_users_stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE department = ?");
$dept_users_stmt->bind_param("s", $user_dept);
$dept_users_stmt->execute();
$dept_member_count = $dept_users_stmt->get_result()->fetch_assoc()['count'];
$dept_users_stmt->close();

$dept_info = $conn->prepare("SELECT * FROM departments WHERE name = ?");
$dept_info->bind_param("s", $user_dept);
$dept_info->execute();
$dept_details = $dept_info->get_result()->fetch_assoc();
$dept_info->close();

$dept_members_stmt = $conn->prepare("SELECT full_name, email, phone, created_at FROM users WHERE department = ? ORDER BY created_at DESC LIMIT 10");
$dept_members_stmt->bind_param("s", $user_dept);
$dept_members_stmt->execute();
$dept_members = $dept_members_stmt->get_result();
$dept_members_stmt->close();

$announcements = $conn->query("SELECT a.*, u.full_name as author FROM announcements a LEFT JOIN users u ON a.created_by = u.id WHERE a.department IS NULL OR a.department = '" . $conn->real_escape_string($user_dept) . "' ORDER BY a.priority DESC, a.created_at DESC LIMIT 5");

$all_dept_stats = $conn->query("SELECT d.name, (SELECT COUNT(*) FROM users WHERE department = d.name) as member_count FROM departments d ORDER BY member_count DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Department Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">Department Portal</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><span class="nav-user">Welcome, <?php echo htmlspecialchars($full_name); ?></span></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard">
        <div class="dash-header">
            <h1>Dashboard</h1>
            <p>Manage your department and stay updated</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card stat-blue">
                <div class="stat-icon">&#128101;</div>
                <div class="stat-info">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Members</p>
                </div>
            </div>
            <div class="stat-card stat-green">
                <div class="stat-icon">&#127963;</div>
                <div class="stat-info">
                    <h3><?php echo $total_depts; ?></h3>
                    <p>Departments</p>
                </div>
            </div>
            <div class="stat-card stat-orange">
                <div class="stat-icon">&#128100;</div>
                <div class="stat-info">
                    <h3><?php echo $dept_member_count; ?></h3>
                    <p><?php echo htmlspecialchars($user_dept); ?> Members</p>
                </div>
            </div>
            <div class="stat-card stat-purple">
                <div class="stat-icon">&#128227;</div>
                <div class="stat-info">
                    <h3><?php echo $announcements->num_rows; ?></h3>
                    <p>Announcements</p>
                </div>
            </div>
        </div>

        <div class="dash-grid">
            <div class="dash-main">
                <div class="dash-card">
                    <div class="card-header">
                        <h2>Your Department: <?php echo htmlspecialchars($user_dept); ?></h2>
                    </div>
                    <div class="card-body">
                        <?php if ($dept_details): ?>
                            <div class="dept-detail-grid">
                                <div class="detail-item">
                                    <strong>Head:</strong>
                                    <span><?php echo htmlspecialchars($dept_details['head']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <strong>Location:</strong>
                                    <span><?php echo htmlspecialchars($dept_details['location']); ?></span>
                                </div>
                                <div class="detail-item detail-full">
                                    <strong>Description:</strong>
                                    <span><?php echo htmlspecialchars($dept_details['description']); ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>No department details available.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="card-header">
                        <h2>Announcements</h2>
                    </div>
                    <div class="card-body">
                        <?php while ($ann = $announcements->fetch_assoc()): ?>
                            <div class="announcement-item">
                                <span class="priority-badge priority-<?php echo $ann['priority']; ?>">
                                    <?php echo ucfirst($ann['priority']); ?>
                                </span>
                                <div class="announcement-content">
                                    <h4><?php echo htmlspecialchars($ann['title']); ?></h4>
                                    <p><?php echo htmlspecialchars($ann['content']); ?></p>
                                    <small>
                                        <?php echo $ann['department'] ? htmlspecialchars($ann['department']) . ' | ' : 'All Departments | '; ?>
                                        <?php echo date('M d, Y', strtotime($ann['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="card-header">
                        <h2>Department Members</h2>
                    </div>
                    <div class="card-body">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($member = $dept_members->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                                        <td><?php echo htmlspecialchars($member['phone']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($member['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="dash-sidebar">
                <div class="dash-card">
                    <div class="card-header">
                        <h2>Profile</h2>
                    </div>
                    <div class="card-body profile-card">
                        <div class="profile-avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
                        <h3><?php echo htmlspecialchars($full_name); ?></h3>
                        <p class="profile-email"><?php echo htmlspecialchars($email); ?></p>
                        <p class="profile-dept"><?php echo htmlspecialchars($user_dept); ?></p>
                        <p class="profile-joined">Joined <?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="card-header">
                        <h2>Department Stats</h2>
                    </div>
                    <div class="card-body">
                        <div class="dept-stat-list">
                            <?php while ($ds = $all_dept_stats->fetch_assoc()): ?>
                                <div class="dept-stat-item">
                                    <span class="dept-stat-name"><?php echo htmlspecialchars($ds['name']); ?></span>
                                    <div class="dept-stat-bar-container">
                                        <div class="dept-stat-bar" style="width: <?php echo $total_users > 0 ? ($ds['member_count'] / $total_users) * 100 : 0; ?>%"></div>
                                    </div>
                                    <span class="dept-stat-count"><?php echo $ds['member_count']; ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="card-header">
                        <h2>Quick Actions</h2>
                    </div>
                    <div class="card-body">
                        <a href="about.php" class="quick-action-btn">View Departments</a>
                        <a href="index.php" class="quick-action-btn">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Department Portal. All rights reserved.</p>
    </footer>
</body>
</html>
