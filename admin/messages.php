<?php
/**
 * Admin Contact Inbox Messages
 */

require_once __DIR__ . '/../includes/functions.php';
requireAdminLogin();

$db = getDB();
$message = '';
$error = '';

// Delete message
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Message deleted successfully.";
    } catch (Exception $e) {
        $error = "Failed to delete message.";
    }
}

// Mark as read
if (isset($_GET['action']) && $_GET['action'] === 'mark_read' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Message marked as read.";
    } catch (Exception $e) {
        $error = "Database update failed.";
    }
}

// Fetch single message if ID passed
$viewMsg = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $viewMsg = $stmt->fetch();
    
    // Automatically mark read when viewed
    if ($viewMsg && !$viewMsg['is_read']) {
        $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$id]);
        $viewMsg['is_read'] = 1;
    }
}

$messagesStmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$allMessages = $messagesStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Inbox | Admin CMS</title>
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
            <li><a href="services.php"><i class="bi bi-stars"></i> Services & Testimonials</a></li>
            <li><a href="messages.php" class="active"><i class="bi bi-envelope-fill"></i> Inbox Messages</a></li>
            <li style="margin-top: auto;"><a href="../index.php" target="_blank"><i class="bi bi-globe"></i> View Public Site</a></li>
            <li><a href="logout.php" style="color: #ef4444;"><i class="bi bi-box-arrow-right"></i> Sign Out</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1>Contact Inbox</h1>
                <p style="color: var(--admin-muted);">Incoming contact messages sent by visitors from your portfolio.</p>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message alert-success" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($error); ?></div>
        <?php endif; ?>

        <?php if ($viewMsg): ?>
            <div class="admin-card" style="border-left: 4px solid var(--admin-accent);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <h2><?php echo sanitize($viewMsg['subject'] ?: 'No Subject'); ?></h2>
                        <p style="color: var(--admin-muted); font-size: 0.9rem; margin-top: 0.25rem;">
                            From: <strong><?php echo sanitize($viewMsg['sender_name']); ?></strong> (<?php echo sanitize($viewMsg['sender_email']); ?>)
                            • Date: <?php echo date('F d, Y \a\t H:i', strtotime($viewMsg['created_at'])); ?>
                        </p>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="mailto:<?php echo sanitize($viewMsg['sender_email']); ?>?subject=RE: <?php echo urlencode($viewMsg['subject']); ?>" class="admin-btn admin-btn-primary"><i class="bi bi-reply-fill"></i> Reply via Email</a>
                        <a href="messages.php?action=delete&id=<?php echo $viewMsg['id']; ?>" onclick="return confirm('Delete message?');" class="admin-btn admin-btn-danger"><i class="bi bi-trash"></i> Delete</a>
                    </div>
                </div>

                <div style="background: rgba(0, 0, 0, 0.2); padding: 1.5rem; border-radius: 8px; font-size: 1.05rem; line-height: 1.8; color: #ffffff; white-space: pre-wrap; margin-top: 1rem;">
                    <?php echo sanitize($viewMsg['message']); ?>
                </div>

                <div style="margin-top: 1.5rem;">
                    <a href="messages.php" style="color: var(--admin-accent); font-weight: 600;">&larr; Back to all messages</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <h3>Inbox Messages (<?php echo count($allMessages); ?>)</h3>

            <?php if (count($allMessages) > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Sender</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Received</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allMessages as $msg): ?>
                                <tr style="<?php echo !$msg['is_read'] ? 'background: rgba(99, 102, 241, 0.08); font-weight: bold;' : ''; ?>">
                                    <td>
                                        <span class="badge-status <?php echo $msg['is_read'] ? 'badge-read' : 'badge-unread'; ?>">
                                            <?php echo $msg['is_read'] ? 'Read' : 'Unread'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo sanitize($msg['sender_name']); ?></td>
                                    <td><?php echo sanitize($msg['sender_email']); ?></td>
                                    <td><?php echo sanitize($msg['subject'] ?: 'No Subject'); ?></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?></td>
                                    <td>
                                        <a href="messages.php?id=<?php echo $msg['id']; ?>" class="admin-btn admin-btn-primary admin-btn-sm">Read</a>
                                        <a href="messages.php?action=delete&id=<?php echo $msg['id']; ?>" onclick="return confirm('Delete message?');" class="admin-btn admin-btn-danger admin-btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--admin-muted); padding: 1rem 0;">Your contact inbox is empty.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
