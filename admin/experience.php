<?php
/**
 * Admin Experience Timeline CRUD Manager
 */

require_once __DIR__ . '/../includes/functions.php';
requireAdminLogin();

$db = getDB();
$message = '';
$error = '';

// Delete Timeline Item
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM experiences WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Experience item deleted.";
    } catch (Exception $e) {
        $error = "Failed to delete item.";
    }
}

// Add/Update Timeline Item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $type         = sanitize($_POST['type'] ?? 'work');
    $role_title   = sanitize($_POST['role_title'] ?? '');
    $organization = sanitize($_POST['organization'] ?? '');
    $location     = sanitize($_POST['location'] ?? '');
    $start_date   = sanitize($_POST['start_date'] ?? '');
    $end_date     = sanitize($_POST['end_date'] ?? 'Present');
    $description  = trim($_POST['description'] ?? '');

    if (!empty($role_title) && !empty($organization)) {
        try {
            if ($id > 0) {
                $stmt = $db->prepare("UPDATE experiences SET type=?, role_title=?, organization=?, location=?, start_date=?, end_date=?, description=? WHERE id=?");
                $stmt->execute([$type, $role_title, $organization, $location, $start_date, $end_date, $description, $id]);
                $message = "Timeline entry updated!";
            } else {
                $stmt = $db->prepare("INSERT INTO experiences (type, role_title, organization, location, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$type, $role_title, $organization, $location, $start_date, $end_date, $description]);
                $message = "Timeline entry added!";
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Role Title and Organization are required.";
    }
}

$editExp = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM experiences WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $editExp = $stmt->fetch();
}

$expStmt = $db->query("SELECT * FROM experiences ORDER BY sort_order ASC, id DESC");
$experiences = $expStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Timeline | Admin CMS</title>
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
            <li><a href="experience.php" class="active"><i class="bi bi-briefcase-fill"></i> Career Timeline</a></li>
            <li><a href="services.php"><i class="bi bi-stars"></i> Services & Testimonials</a></li>
            <li><a href="messages.php"><i class="bi bi-envelope-fill"></i> Inbox Messages</a></li>
            <li style="margin-top: auto;"><a href="../index.php" target="_blank"><i class="bi bi-globe"></i> View Public Site</a></li>
            <li><a href="logout.php" style="color: #ef4444;"><i class="bi bi-box-arrow-right"></i> Sign Out</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1>Career & Education Timeline</h1>
                <p style="color: var(--admin-muted);">Manage employment milestones, company positions, and university degrees.</p>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message alert-success" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($error); ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <h3><?php echo $editExp ? 'Edit Timeline Entry' : 'Add New Career / Education Milestone'; ?></h3>

            <form method="POST" action="experience.php" style="margin-top: 1rem;">
                <?php if ($editExp): ?>
                    <input type="hidden" name="id" value="<?php echo $editExp['id']; ?>">
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 1fr 2fr 2fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control">
                            <option value="work" <?php echo ($editExp['type'] ?? '') === 'work' ? 'selected' : ''; ?>>Work Experience</option>
                            <option value="education" <?php echo ($editExp['type'] ?? '') === 'education' ? 'selected' : ''; ?>>Education & Degree</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role Title / Degree</label>
                        <input type="text" name="role_title" class="form-control" placeholder="Senior Full-Stack Developer" value="<?php echo sanitize($editExp['role_title'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Organization / University</label>
                        <input type="text" name="organization" class="form-control" placeholder="International Tech Solutions" value="<?php echo sanitize($editExp['organization'] ?? ''); ?>" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="Kigali, Rwanda / Remote" value="<?php echo sanitize($editExp['location'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="text" name="start_date" class="form-control" placeholder="2022" value="<?php echo sanitize($editExp['start_date'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input type="text" name="end_date" class="form-control" placeholder="Present" value="<?php echo sanitize($editExp['end_date'] ?? 'Present'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Key Responsibilities & Achievements</label>
                    <textarea name="description" class="form-control" rows="3" required><?php echo sanitize($editExp['description'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-save"></i> Save Milestone</button>
                <?php if ($editExp): ?>
                    <a href="experience.php" class="admin-btn admin-btn-danger">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="admin-card">
            <h3>Existing Timeline Milestones</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Role / Degree</th>
                            <th>Organization</th>
                            <th>Period</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($experiences as $ex): ?>
                            <tr>
                                <td><span class="badge-status badge-read"><?php echo strtoupper(sanitize($ex['type'])); ?></span></td>
                                <td><strong><?php echo sanitize($ex['role_title']); ?></strong></td>
                                <td><?php echo sanitize($ex['organization']); ?></td>
                                <td><?php echo sanitize($ex['start_date']); ?> - <?php echo sanitize($ex['end_date']); ?></td>
                                <td>
                                    <a href="experience.php?action=edit&id=<?php echo $ex['id']; ?>" class="admin-btn admin-btn-primary admin-btn-sm">Edit</a>
                                    <a href="experience.php?action=delete&id=<?php echo $ex['id']; ?>" onclick="return confirm('Delete this timeline item?');" class="admin-btn admin-btn-danger admin-btn-sm">Delete</a>
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
