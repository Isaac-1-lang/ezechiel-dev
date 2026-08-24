<?php
/**
 * Admin Dashboard Overview
 */

require_once __DIR__ . '/../includes/functions.php';
requireAdminLogin();

$db = getDB();

// Gather System Stats
$totalProjects = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$totalSkills   = $db->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$totalMessages = $db->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$unreadMsgs    = $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();

// Fetch Recent Unread Messages
$recentMsgsStmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
$recentMsgs = $recentMsgsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Portfolio CMS</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <i class="bi bi-speedometer2" style="color: var(--admin-accent);"></i> CMS Dashboard
        </div>

        <ul class="admin-nav">
            <li><a href="index.php" class="active"><i class="bi bi-house-door-fill"></i> Overview</a></li>
            <li><a href="profile.php"><i class="bi bi-person-lines-fill"></i> Profile & Life Story</a></li>
            <li><a href="projects.php"><i class="bi bi-folder-fill"></i> Projects Manager</a></li>
            <li><a href="skills.php"><i class="bi bi-code-square"></i> Skills Matrix</a></li>
            <li><a href="experience.php"><i class="bi bi-briefcase-fill"></i> Career Timeline</a></li>
            <li><a href="services.php"><i class="bi bi-stars"></i> Services & Testimonials</a></li>
            <li><a href="messages.php"><i class="bi bi-envelope-fill"></i> Inbox Messages <?php if ($unreadMsgs > 0): ?><span class="badge-status badge-unread"><?php echo $unreadMsgs; ?></span><?php endif; ?></a></li>
            <li style="margin-top: auto;"><a href="../index.php" target="_blank"><i class="bi bi-globe"></i> View Public Site</a></li>
            <li><a href="logout.php" style="color: #ef4444;"><i class="bi bi-box-arrow-right"></i> Sign Out</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1 style="font-size: 1.8rem; margin-bottom: 0.25rem;">Dashboard Overview</h1>
                <p style="color: var(--admin-muted);">Welcome back, Admin! Manage your international portfolio content.</p>
            </div>
            <a href="profile.php" class="admin-btn admin-btn-primary"><i class="bi bi-pencil-square"></i> Edit Profile</a>
        </div>

        <!-- Metric Stat Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div class="admin-card">
                <span style="color: var(--admin-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700;">Total Projects</span>
                <h2 style="font-size: 2.2rem; margin-top: 0.5rem; color: #ffffff;"><?php echo $totalProjects; ?></h2>
            </div>
            <div class="admin-card">
                <span style="color: var(--admin-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700;">Technical Skills</span>
                <h2 style="font-size: 2.2rem; margin-top: 0.5rem; color: #6366f1;"><?php echo $totalSkills; ?></h2>
            </div>
            <div class="admin-card">
                <span style="color: var(--admin-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700;">Total Messages</span>
                <h2 style="font-size: 2.2rem; margin-top: 0.5rem; color: #06b6d4;"><?php echo $totalMessages; ?></h2>
            </div>
            <div class="admin-card">
                <span style="color: var(--admin-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 700;">Unread Inquiries</span>
                <h2 style="font-size: 2.2rem; margin-top: 0.5rem; color: #ef4444;"><?php echo $unreadMsgs; ?></h2>
            </div>
        </div>

        <!-- Recent Inbox Messages Table -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3><i class="bi bi-inbox-fill" style="color: var(--admin-accent);"></i> Recent Incoming Messages</h3>
                <a href="messages.php" style="color: var(--admin-accent); font-size: 0.9rem; font-weight: 600; text-decoration: none;">View All Messages &rarr;</a>
            </div>

            <?php if (count($recentMsgs) > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Sender</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date Received</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentMsgs as $msg): ?>
                                <tr>
                                    <td>
                                        <span class="badge-status <?php echo $msg['is_read'] ? 'badge-read' : 'badge-unread'; ?>">
                                            <?php echo $msg['is_read'] ? 'Read' : 'Unread'; ?>
                                        </span>
                                    </td>
                                    <td><strong><?php echo sanitize($msg['sender_name']); ?></strong></td>
                                    <td><?php echo sanitize($msg['sender_email']); ?></td>
                                    <td><?php echo sanitize($msg['subject']); ?></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?></td>
                                    <td>
                                        <a href="messages.php?id=<?php echo $msg['id']; ?>" class="admin-btn admin-btn-primary admin-btn-sm">Open Inbox</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--admin-muted); padding: 1rem 0;">No messages received yet.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
