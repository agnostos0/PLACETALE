<?php require __DIR__ . '/auth.php'; if (!is_logged_in()) { header('Location: login.html?error=login_required'); exit; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile - PlaceTale</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.html" class="logo">PlaceTale</a>
                <ul class="nav-links">
                    <li><a href="index.html"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="stories.html"><i class="fas fa-book"></i> Stories</a></li>
                    <li><a href="events.html"><i class="fas fa-calendar"></i> Events</a></li>
                    <li><a href="submit.html"><i class="fas fa-plus"></i> Share a Tale</a></li>
                    <li><a href="profile.php" class="active"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="content-section profile-section">
            <div class="profile-card">
                <div class="profile-info">
                    <img src="https://i.pravatar.cc/160" alt="Avatar" class="profile-avatar" />
                    <h3><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></h3>
                    <p><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
                </div>
                <div class="profile-actions">
                    <h4>Account</h4>
                    <ul class="user-stories">
                        <li><i class="fas fa-envelope"></i> Email verified</li>
                        <li><i class="fas fa-calendar"></i> Member since <?php echo date('M Y'); ?></li>
                    </ul>
                    <a class="btn btn-secondary" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Go to Dashboard</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <p>&copy; 2024 PlaceTale. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>


