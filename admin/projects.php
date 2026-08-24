<?php
/**
 * Admin Projects CRUD Manager
 */

require_once __DIR__ . '/../includes/functions.php';
requireAdminLogin();

$db = getDB();
$message = '';
$error = '';

// Handle Delete Project
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Project deleted successfully.";
    } catch (Exception $e) {
        $error = "Failed to delete project.";
    }
}

// Handle Add/Update Project
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title       = sanitize($_POST['title'] ?? '');
    $category    = sanitize($_POST['category'] ?? 'Web Apps');
    $summary     = sanitize($_POST['summary'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tech_stack  = sanitize($_POST['tech_stack'] ?? '');
    $image_url   = sanitize($_POST['image_url'] ?? '');
    $demo_link   = sanitize($_POST['demo_link'] ?? '#');
    $github_link = sanitize($_POST['github_link'] ?? '#');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $sort_order  = (int)($_POST['sort_order'] ?? 0);

    if (!empty($title) && !empty($summary) && !empty($image_url)) {
        try {
            if ($id > 0) {
                // Update
                $stmt = $db->prepare("UPDATE projects SET title=?, category=?, summary=?, description=?, tech_stack=?, image_url=?, demo_link=?, github_link=?, is_featured=?, sort_order=? WHERE id=?");
                $stmt->execute([$title, $category, $summary, $description, $tech_stack, $image_url, $demo_link, $github_link, $is_featured, $sort_order, $id]);
                $message = "Project updated successfully!";
            } else {
                // Insert
                $stmt = $db->prepare("INSERT INTO projects (title, category, summary, description, tech_stack, image_url, demo_link, github_link, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $category, $summary, $description, $tech_stack, $image_url, $demo_link, $github_link, $is_featured, $sort_order]);
                $message = "New project added successfully!";
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Title, summary, and image URL are required.";
    }
}

// Fetch edit project if ID passed
$editProject = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $editProject = $stmt->fetch();
}

$projectsStmt = $db->query("SELECT * FROM projects ORDER BY sort_order ASC, id DESC");
$projects = $projectsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Manager | Admin CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-brand"><i class="bi bi-speedometer2"></i> CMS Dashboard</div>
        <ul class="admin-nav">
            <li><a href="index.php"><i class="bi bi-house-door-fill"></i> Overview</a></li>
            <li><a href="profile.php"><i class="bi bi-person-lines-fill"></i> Profile & Life Story</a></li>
            <li><a href="projects.php" class="active"><i class="bi bi-folder-fill"></i> Projects Manager</a></li>
            <li><a href="skills.php"><i class="bi bi-code-square"></i> Skills Matrix</a></li>
            <li><a href="experience.php"><i class="bi bi-briefcase-fill"></i> Career Timeline</a></li>
            <li><a href="services.php"><i class="bi bi-stars"></i> Services & Testimonials</a></li>
            <li><a href="messages.php"><i class="bi bi-envelope-fill"></i> Inbox Messages</a></li>
            <li style="margin-top: auto;"><a href="../index.php" target="_blank"><i class="bi bi-globe"></i> View Public Site</a></li>
            <li><a href="logout.php" style="color: #ef4444;"><i class="bi bi-box-arrow-right"></i> Sign Out</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1>Projects Manager</h1>
                <p style="color: var(--admin-muted);">Add, edit, or remove showcase projects from your international portfolio.</p>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message alert-success" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($error); ?></div>
        <?php endif; ?>

        <!-- Form for Add / Edit Project -->
        <div class="admin-card">
            <h3><i class="bi bi-plus-circle-fill" style="color: var(--admin-accent);"></i> <?php echo $editProject ? 'Edit Project' : 'Add New Showcase Project'; ?></h3>

            <form method="POST" action="projects.php" style="margin-top: 1.25rem;">
                <?php if ($editProject): ?>
                    <input type="hidden" name="id" value="<?php echo $editProject['id']; ?>">
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Project Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo sanitize($editProject['title'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="Web Apps" <?php echo ($editProject['category'] ?? '') === 'Web Apps' ? 'selected' : ''; ?>>Web Apps</option>
                            <option value="Cloud & APIs" <?php echo ($editProject['category'] ?? '') === 'Cloud & APIs' ? 'selected' : ''; ?>>Cloud & APIs</option>
                            <option value="Fintech" <?php echo ($editProject['category'] ?? '') === 'Fintech' ? 'selected' : ''; ?>>Fintech</option>
                            <option value="Mobile Apps" <?php echo ($editProject['category'] ?? '') === 'Mobile Apps' ? 'selected' : ''; ?>>Mobile Apps</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Short Summary (Card View)</label>
                    <input type="text" name="summary" class="form-control" value="<?php echo sanitize($editProject['summary'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Full Case Study Description (Modal View)</label>
                    <textarea name="description" class="form-control" rows="3" required><?php echo sanitize($editProject['description'] ?? ''); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Tech Stack (Comma-separated)</label>
                        <input type="text" name="tech_stack" class="form-control" placeholder="PHP, MySQL, JavaScript, Leaflet" value="<?php echo sanitize($editProject['tech_stack'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image URL</label>
                        <input type="text" name="image_url" class="form-control" value="<?php echo sanitize($editProject['image_url'] ?? ''); ?>" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Live Demo Link</label>
                        <input type="text" name="demo_link" class="form-control" value="<?php echo sanitize($editProject['demo_link'] ?? '#'); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">GitHub Repository Link</label>
                        <input type="text" name="github_link" class="form-control" value="<?php echo sanitize($editProject['github_link'] ?? '#'); ?>">
                    </div>
                </div>

                <div style="display: flex; gap: 2rem; align-items: center; margin-bottom: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?php echo !empty($editProject['is_featured']) ? 'checked' : ''; ?>>
                        <span>Mark as Featured Project</span>
                    </label>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <label>Sort Order:</label>
                        <input type="number" name="sort_order" class="form-control" style="width: 80px;" value="<?php echo (int)($editProject['sort_order'] ?? 0); ?>">
                    </div>
                </div>

                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check-circle-fill"></i> <?php echo $editProject ? 'Save Changes' : 'Publish Project'; ?>
                </button>
                <?php if ($editProject): ?>
                    <a href="projects.php" class="admin-btn admin-btn-danger">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Projects Table -->
        <div class="admin-card">
            <h3>Existing Projects</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Tech Stack</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $proj): ?>
                            <tr>
                                <td><img src="<?php echo sanitize($proj['image_url']); ?>" width="50" height="35" style="object-fit: cover; border-radius: 4px;"></td>
                                <td><strong><?php echo sanitize($proj['title']); ?></strong></td>
                                <td><span class="badge-status badge-read"><?php echo sanitize($proj['category']); ?></span></td>
                                <td><small><?php echo sanitize($proj['tech_stack']); ?></small></td>
                                <td><?php echo $proj['is_featured'] ? '⭐ Yes' : 'No'; ?></td>
                                <td>
                                    <a href="projects.php?action=edit&id=<?php echo $proj['id']; ?>" class="admin-btn admin-btn-primary admin-btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="projects.php?action=delete&id=<?php echo $proj['id']; ?>" onclick="return confirm('Are you sure you want to delete this project?');" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
