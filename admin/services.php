<?php
/**
 * Admin Services & Testimonials Manager
 */

require_once __DIR__ . '/../includes/functions.php';
requireAdminLogin();

$db = getDB();
$message = '';
$error = '';

// Handle Service Add/Delete
if (isset($_POST['action']) && $_POST['action'] === 'add_service') {
    $title       = sanitize($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon        = sanitize($_POST['icon'] ?? 'bi bi-laptop');
    $features    = sanitize($_POST['features'] ?? '');

    if (!empty($title)) {
        $stmt = $db->prepare("INSERT INTO services (title, description, icon, features) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $icon, $features]);
        $message = "Service added successfully.";
    }
}

if (isset($_GET['del_service'])) {
    $db->prepare("DELETE FROM services WHERE id = ?")->execute([(int)$_GET['del_service']]);
    $message = "Service deleted.";
}

// Handle Testimonial Add/Delete
if (isset($_POST['action']) && $_POST['action'] === 'add_testimonial') {
    $name     = sanitize($_POST['client_name'] ?? '');
    $role     = sanitize($_POST['client_role'] ?? '');
    $company  = sanitize($_POST['company'] ?? '');
    $rating   = (int)($_POST['rating'] ?? 5);
    $msgText  = trim($_POST['message'] ?? '');
    $avatar   = sanitize($_POST['avatar_url'] ?? '');

    if (!empty($name) && !empty($msgText)) {
        $stmt = $db->prepare("INSERT INTO testimonials (client_name, client_role, company, rating, message, avatar_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $role, $company, $rating, $msgText, $avatar]);
        $message = "Testimonial added successfully.";
    }
}

if (isset($_GET['del_testimonial'])) {
    $db->prepare("DELETE FROM testimonials WHERE id = ?")->execute([(int)$_GET['del_testimonial']]);
    $message = "Testimonial deleted.";
}

$servicesStmt = $db->query("SELECT * FROM services ORDER BY id DESC");
$services = $servicesStmt->fetchAll();

$testimonialsStmt = $db->query("SELECT * FROM testimonials ORDER BY id DESC");
$testimonials = $testimonialsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services & Testimonials | Admin CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-brand"><i class="bi bi-speedometer2"></i> CMS Dashboard</div>
        <ul class="admin-nav">
            <li><a href="index.php"><i class="bi bi-house-door-fill"></i> Overview</a></li>
            <li><a href="profile.php"><i class="bi bi-person-lines-fill"></i> Profile & Life Story</a></li>
            <li><a href="projects.php"><i class="bi bi-folder-fill"></i> Projects Manager</a></li>
            <li><a href="skills.php"><i class="bi bi-code-square"></i> Skills Matrix</a></li>
            <li><a href="experience.php"><i class="bi bi-briefcase-fill"></i> Career Timeline</a></li>
            <li><a href="services.php" class="active"><i class="bi bi-stars"></i> Services & Testimonials</a></li>
            <li><a href="messages.php"><i class="bi bi-envelope-fill"></i> Inbox Messages</a></li>
            <li style="margin-top: auto;"><a href="../index.php" target="_blank"><i class="bi bi-globe"></i> View Public Site</a></li>
            <li><a href="logout.php" style="color: #ef4444;"><i class="bi bi-box-arrow-right"></i> Sign Out</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1>Services & Testimonials Manager</h1>
                <p style="color: var(--admin-muted);">Manage your service offerings and client recommendations.</p>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message alert-success" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($message); ?></div>
        <?php endif; ?>

        <!-- 1. Services Section -->
        <div class="admin-card">
            <h3><i class="bi bi-laptop-fill" style="color: var(--admin-accent);"></i> Add New Service</h3>
            <form method="POST" action="services.php" style="margin-top: 1rem;">
                <input type="hidden" name="action" value="add_service">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Service Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Custom Web Development" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="icon" class="form-control" placeholder="bi bi-laptop">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Features Highlight</label>
                    <input type="text" name="features" class="form-control" placeholder="PHP, MySQL, Responsive Design">
                </div>
                <button type="submit" class="admin-btn admin-btn-primary">Add Service</button>
            </form>

            <h4 style="margin-top: 2rem;">Existing Services</h4>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $srv): ?>
                            <tr>
                                <td><i class="<?php echo sanitize($srv['icon']); ?>" style="color: #6366f1; font-size: 1.2rem;"></i></td>
                                <td><strong><?php echo sanitize($srv['title']); ?></strong></td>
                                <td><small><?php echo sanitize($srv['description']); ?></small></td>
                                <td><a href="services.php?del_service=<?php echo $srv['id']; ?>" onclick="return confirm('Delete service?');" class="admin-btn admin-btn-danger admin-btn-sm">Delete</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Testimonials Section -->
        <div class="admin-card">
            <h3><i class="bi bi-quote" style="color: var(--admin-accent);"></i> Add Client Testimonial</h3>
            <form method="POST" action="services.php" style="margin-top: 1rem;">
                <input type="hidden" name="action" value="add_testimonial">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Client Name</label>
                        <input type="text" name="client_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input type="text" name="client_role" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company</label>
                        <input type="text" name="company" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rating (1-5)</label>
                        <input type="number" name="rating" class="form-control" min="1" max="5" value="5">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Client Recommendation Message</label>
                    <textarea name="message" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Avatar Image URL</label>
                    <input type="text" name="avatar_url" class="form-control" placeholder="https://images.unsplash.com/...">
                </div>
                <button type="submit" class="admin-btn admin-btn-primary">Add Testimonial</button>
            </form>

            <h4 style="margin-top: 2rem;">Existing Recommendations</h4>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Role & Company</th>
                            <th>Rating</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($testimonials as $t): ?>
                            <tr>
                                <td><strong><?php echo sanitize($t['client_name']); ?></strong></td>
                                <td><?php echo sanitize($t['client_role']); ?>, <?php echo sanitize($t['company']); ?></td>
                                <td>⭐ <?php echo (int)$t['rating']; ?></td>
                                <td><small>"<?php echo sanitize($t['message']); ?>"</small></td>
                                <td><a href="services.php?del_testimonial=<?php echo $t['id']; ?>" onclick="return confirm('Delete testimonial?');" class="admin-btn admin-btn-danger admin-btn-sm">Delete</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
