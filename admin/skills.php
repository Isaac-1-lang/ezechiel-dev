<?php
/**
 * Admin Skills Matrix CRUD Manager
 */

require_once __DIR__ . '/../includes/functions.php';
requireAdminLogin();

$db = getDB();
$message = '';
$error = '';

// Delete Skill
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM skills WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Skill removed successfully.";
    } catch (Exception $e) {
        $error = "Failed to remove skill.";
    }
}

// Add/Update Skill
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $category    = sanitize($_POST['category'] ?? 'Frontend');
    $name        = sanitize($_POST['name'] ?? '');
    $proficiency = (int)($_POST['proficiency_percent'] ?? 85);
    $icon_class  = sanitize($_POST['icon_class'] ?? 'bi bi-code-slash');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);

    if (!empty($name)) {
        try {
            if ($id > 0) {
                $stmt = $db->prepare("UPDATE skills SET category=?, name=?, proficiency_percent=?, icon_class=?, sort_order=? WHERE id=?");
                $stmt->execute([$category, $name, $proficiency, $icon_class, $sort_order, $id]);
                $message = "Skill updated successfully!";
            } else {
                $stmt = $db->prepare("INSERT INTO skills (category, name, proficiency_percent, icon_class, sort_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$category, $name, $proficiency, $icon_class, $sort_order]);
                $message = "New skill added!";
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Skill name is required.";
    }
}

$editSkill = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $editSkill = $stmt->fetch();
}

$skillsStmt = $db->query("SELECT * FROM skills ORDER BY category ASC, sort_order ASC");
$skills = $skillsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills Matrix | Admin CMS</title>
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
            <li><a href="skills.php" class="active"><i class="bi bi-code-square"></i> Skills Matrix</a></li>
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
                <h1>Skills Matrix Manager</h1>
                <p style="color: var(--admin-muted);">Manage technical competencies, categories, and proficiency levels.</p>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message alert-success" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($error); ?></div>
        <?php endif; ?>

        <!-- Form Add/Edit -->
        <div class="admin-card">
            <h3><?php echo $editSkill ? 'Edit Skill' : 'Add New Technical Skill'; ?></h3>
            <form method="POST" action="skills.php" style="margin-top: 1rem;">
                <?php if ($editSkill): ?>
                    <input type="hidden" name="id" value="<?php echo $editSkill['id']; ?>">
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="Backend" <?php echo ($editSkill['category'] ?? '') === 'Backend' ? 'selected' : ''; ?>>Backend</option>
                            <option value="Frontend" <?php echo ($editSkill['category'] ?? '') === 'Frontend' ? 'selected' : ''; ?>>Frontend</option>
                            <option value="DevOps" <?php echo ($editSkill['category'] ?? '') === 'DevOps' ? 'selected' : ''; ?>>DevOps</option>
                            <option value="Cloud" <?php echo ($editSkill['category'] ?? '') === 'Cloud' ? 'selected' : ''; ?>>Cloud</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Skill Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. PHP 8 & PDO" value="<?php echo sanitize($editSkill['name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Proficiency % (0 - 100)</label>
                        <input type="number" name="proficiency_percent" class="form-control" min="10" max="100" value="<?php echo (int)($editSkill['proficiency_percent'] ?? 85); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bootstrap Icon Class</label>
                        <input type="text" name="icon_class" class="form-control" placeholder="bi bi-filetype-php" value="<?php echo sanitize($editSkill['icon_class'] ?? 'bi bi-code-slash'); ?>">
                    </div>
                </div>

                <button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-save"></i> <?php echo $editSkill ? 'Update Skill' : 'Add Skill'; ?></button>
                <?php if ($editSkill): ?>
                    <a href="skills.php" class="admin-btn admin-btn-danger">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Skills List -->
        <div class="admin-card">
            <h3>Current Skills</h3>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Proficiency</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skills as $s): ?>
                            <tr>
                                <td><span class="badge-status badge-read"><?php echo sanitize($s['category']); ?></span></td>
                                <td><i class="<?php echo sanitize($s['icon_class']); ?>" style="font-size: 1.2rem; color: #6366f1;"></i></td>
                                <td><strong><?php echo sanitize($s['name']); ?></strong></td>
                                <td><?php echo (int)$s['proficiency_percent']; ?>%</td>
                                <td>
                                    <a href="skills.php?action=edit&id=<?php echo $s['id']; ?>" class="admin-btn admin-btn-primary admin-btn-sm">Edit</a>
                                    <a href="skills.php?action=delete&id=<?php echo $s['id']; ?>" onclick="return confirm('Delete this skill?');" class="admin-btn admin-btn-danger admin-btn-sm">Delete</a>
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
