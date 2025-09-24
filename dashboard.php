<?php require __DIR__ . '/auth.php'; if (!is_logged_in()) { header('Location: login.html?error=login_required'); exit; } ?>
<?php require __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Dashboard - PlaceTale</title>
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
                    <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="content-section">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Student'); ?></h2>
            <div class="features">
                <div class="feature">
                    <h3>My Drafts</h3>
                    <p>View and edit your in-progress tales.</p>
                </div>
                <div class="feature">
                    <h3>Submitted Tales</h3>
                    <p>Track status of tales pending review.</p>
                </div>
                <div class="feature">
                    <h3>Events</h3>
                    <p>Find workshops and meetups to improve storytelling.</p>
                </div>
            </div>
            <div style="margin-top:2rem">
                <h3>Latest Events</h3>
                <div class="events-list" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1rem;margin-top:0.8rem">
                <?php
                try {
                    $pdo = get_pdo();
                    $stmt = $pdo->query('SELECT title, event_date, place, description FROM events ORDER BY event_date DESC LIMIT 5');
                    foreach ($stmt as $ev) {
                        echo '<div class="event-card">';
                        echo '<h3>' . htmlspecialchars($ev['title']) . '</h3>';
                        echo '<div class="event-meta"><i class="fas fa-calendar"></i> ' . htmlspecialchars(date('M d, Y', strtotime($ev['event_date']))) . ' &nbsp; • &nbsp; <i class="fas fa-map-marker-alt"></i> ' . htmlspecialchars($ev['place']) . '</div>';
                        echo '<p>' . htmlspecialchars($ev['description']) . '</p>';
                        echo '</div>';
                    }
                } catch (Throwable $e) {
                    echo '<p>Could not load events.</p>';
                }
                ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <p>&copy; 2024 PlaceTale. All rights reserved.</p>
            <div class="footer-links">
                <a href="about.html">About Us</a>
                <a href="faq.html">FAQ</a>
                <a href="terms.html">Terms & Conditions</a>
            </div>
        </div>
    </footer>
</body>
&nbsp;</html>


