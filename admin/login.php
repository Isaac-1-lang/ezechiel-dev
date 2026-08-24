<?php
/**
 * Admin Login Interface
 */

require_once __DIR__ . '/../includes/functions.php';

$error = '';

if (isAdminLoggedIn()) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user'] = $user['username'];
                $_SESSION['admin_id'] = $user['id'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid admin username or password.";
            }
        } catch (Exception $e) {
            $error = "Database authentication error.";
        }
    } else {
        $error = "Please fill in both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Portfolio CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: var(--bg-primary);">
    
    <div class="glass-card" style="width: 100%; max-width: 420px; padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="brand-logo" style="justify-content: center; margin-bottom: 1rem;">
                <div class="logo-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <span>CMS Admin Panel</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Default Credentials: <code>admin</code> / <code>admin123</code></p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="display: block; margin-bottom: 1.5rem;">
                <?php echo sanitize($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label class="form-label">Admin Username</label>
                <input type="text" name="username" class="form-control" placeholder="admin" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem; margin-top: 1rem;">
                <i class="bi bi-box-arrow-in-right"></i> Sign In to Dashboard
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="../index.php" style="font-size: 0.85rem; color: var(--text-muted);"><i class="bi bi-arrow-left"></i> Back to Public Portfolio</a>
        </div>
    </div>

</body>
</html>
