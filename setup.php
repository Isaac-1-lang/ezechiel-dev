<?php
/**
 * Database Setup & Initialization Script
 */

require_once __DIR__ . '/config/db.php';

$message = '';
$error = '';

if (isset($_GET['run']) && $_GET['run'] === 'true') {
    try {
        $db = getDB();
        $sqlFile = __DIR__ . '/database/schema.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $db->exec($sql);
            $message = "Database schema successfully initialized and default seed data loaded!";
        } else {
            $error = "database/schema.sql file not found.";
        }
    } catch (Exception $e) {
        $error = "Setup failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Database Setup Utility</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: var(--bg-primary);">

    <div class="glass-card" style="max-width: 540px; width: 90%; padding: 2.5rem; text-align: center;">
        <div class="brand-logo" style="justify-content: center; margin-bottom: 1.5rem;">
            <div class="logo-icon"><i class="bi bi-database-gear"></i></div>
            <span>Portfolio Setup Utility</span>
        </div>

        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            This utility will create the <strong>portfolio_db</strong> MySQL database, construct all required tables (profile, projects, skills, experiences, services, testimonials, contact_messages, admin_users), and seed initial data.
        </p>

        <?php if (!empty($message)): ?>
            <div class="alert-message alert-success" style="display: block; margin-bottom: 1.5rem;">
                <?php echo sanitize($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="display: block; margin-bottom: 1.5rem;">
                <?php echo sanitize($error); ?>
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="setup.php?run=true" class="btn btn-primary">
                <i class="bi bi-play-circle-fill"></i> Initialize Database Now
            </a>
            <a href="index.php" class="btn btn-outline">
                <i class="bi bi-globe"></i> View Portfolio
            </a>
        </div>

        <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1rem; font-size: 0.85rem; color: var(--text-muted);">
            Default Admin Login: <strong>admin</strong> / <strong>admin123</strong>
        </div>
    </div>

</body>
</html>
