<?php
/**
 * Admin Profile & Life Story Editor
 */

require_once __DIR__ . '/../includes/functions.php';
requireAdminLogin();

$db = getDB();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name    = sanitize($_POST['full_name'] ?? '');
    $title        = sanitize($_POST['title'] ?? '');
    $tagline      = sanitize($_POST['tagline'] ?? '');
    $bio          = trim($_POST['bio'] ?? '');
    $life_story   = trim($_POST['life_story'] ?? '');
    $philosophy   = trim($_POST['philosophy'] ?? '');
    $location     = sanitize($_POST['location'] ?? '');
    $country      = sanitize($_POST['country'] ?? '');
    $latitude     = (float)($_POST['latitude'] ?? -1.9441);
    $longitude    = (float)($_POST['longitude'] ?? 30.0619);
    $timezone     = sanitize($_POST['timezone'] ?? 'Africa/Kigali');
    $availability = sanitize($_POST['availability'] ?? '');
    $email        = sanitize($_POST['email'] ?? '');
    $phone        = sanitize($_POST['phone'] ?? '');
    $github       = sanitize($_POST['github'] ?? '');
    $linkedin     = sanitize($_POST['linkedin'] ?? '');
    $twitter      = sanitize($_POST['twitter'] ?? '');
    $whatsapp     = sanitize($_POST['whatsapp'] ?? '');
    $resume_url   = sanitize($_POST['resume_url'] ?? '');
    $profile_image= sanitize($_POST['profile_image'] ?? '');

    try {
        $stmt = $db->prepare("UPDATE profile SET 
            full_name = ?, title = ?, tagline = ?, bio = ?, life_story = ?, philosophy = ?,
            location = ?, country = ?, latitude = ?, longitude = ?, timezone = ?, availability = ?,
            email = ?, phone = ?, github = ?, linkedin = ?, twitter = ?, whatsapp = ?,
            resume_url = ?, profile_image = ? WHERE id = 1");
        
        $stmt->execute([
            $full_name, $title, $tagline, $bio, $life_story, $philosophy,
            $location, $country, $latitude, $longitude, $timezone, $availability,
            $email, $phone, $github, $linkedin, $twitter, $whatsapp,
            $resume_url, $profile_image
        ]);

        // Check if admin password change requested
        $new_password = $_POST['new_password'] ?? '';
        if (!empty($new_password)) {
            $passHash = password_hash($new_password, PASSWORD_DEFAULT);
            $pStmt = $db->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
            $pStmt->execute([$passHash, $_SESSION['admin_id']]);
            $message = "Profile and Admin Password updated successfully!";
        } else {
            $message = "Profile details updated successfully!";
        }
    } catch (Exception $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}

$profile = getProfileData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Admin CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-brand"><i class="bi bi-speedometer2"></i> CMS Dashboard</div>
        <ul class="admin-nav">
            <li><a href="index.php"><i class="bi bi-house-door-fill"></i> Overview</a></li>
            <li><a href="profile.php" class="active"><i class="bi bi-person-lines-fill"></i> Profile & Life Story</a></li>
            <li><a href="projects.php"><i class="bi bi-folder-fill"></i> Projects Manager</a></li>
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
                <h1>Developer Profile & Life Story</h1>
                <p style="color: var(--admin-muted);">Update personal details, bio, philosophy, location map coordinates, and links.</p>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-message alert-success" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-message alert-error" style="display: block; margin-bottom: 1.5rem;"><?php echo sanitize($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="profile.php" class="admin-card">
            <h3 style="margin-bottom: 1.5rem; color: var(--admin-accent);"><i class="bi bi-person-badge"></i> Basic Information</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?php echo sanitize($profile['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Professional Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo sanitize($profile['title']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tagline (Hero Header)</label>
                <input type="text" name="tagline" class="form-control" value="<?php echo sanitize($profile['tagline']); ?>" required>
            </div>

            <h3 style="margin: 2rem 0 1.5rem 0; color: var(--admin-accent);"><i class="bi bi-journal-text"></i> Biography & Life Story</h3>

            <div class="form-group">
                <label class="form-label">Biography (Technical Summary)</label>
                <textarea name="bio" class="form-control" rows="4" required><?php echo sanitize($profile['bio']); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Life Story (Personal Journey & Roots)</label>
                <textarea name="life_story" class="form-control" rows="5" required><?php echo sanitize($profile['life_story']); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Life Philosophy & Core Values</label>
                <textarea name="philosophy" class="form-control" rows="4" required><?php echo sanitize($profile['philosophy']); ?></textarea>
            </div>

            <h3 style="margin: 2rem 0 1.5rem 0; color: var(--admin-accent);"><i class="bi bi-geo-alt"></i> Location & Map Coordinates</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">City, Country</label>
                    <input type="text" name="location" class="form-control" value="<?php echo sanitize($profile['location']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" class="form-control" value="<?php echo sanitize($profile['latitude']); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" class="form-control" value="<?php echo sanitize($profile['longitude']); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Timezone</label>
                    <input type="text" name="timezone" class="form-control" value="<?php echo sanitize($profile['timezone']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Work Availability Status Badge</label>
                <input type="text" name="availability" class="form-control" value="<?php echo sanitize($profile['availability']); ?>">
            </div>

            <h3 style="margin: 2rem 0 1.5rem 0; color: var(--admin-accent);"><i class="bi bi-link-45deg"></i> Contact & Social Media</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Public Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo sanitize($profile['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone / WhatsApp</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo sanitize($profile['phone']); ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">GitHub URL</label>
                    <input type="text" name="github" class="form-control" value="<?php echo sanitize($profile['github']); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="text" name="linkedin" class="form-control" value="<?php echo sanitize($profile['linkedin']); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter / X URL</label>
                    <input type="text" name="twitter" class="form-control" value="<?php echo sanitize($profile['twitter']); ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Profile Image URL</label>
                    <input type="text" name="profile_image" class="form-control" value="<?php echo sanitize($profile['profile_image']); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Resume / CV Link</label>
                    <input type="text" name="resume_url" class="form-control" value="<?php echo sanitize($profile['resume_url']); ?>">
                </div>
            </div>

            <h3 style="margin: 2rem 0 1.5rem 0; color: #ef4444;"><i class="bi bi-key-fill"></i> Change Admin Password (Optional)</h3>
            <div class="form-group">
                <label class="form-label">New Password (Leave blank to keep current password)</label>
                <input type="password" name="new_password" class="form-control" placeholder="New Password">
            </div>

            <button type="submit" class="admin-btn admin-btn-primary" style="padding: 0.85rem 2rem; font-size: 1rem; margin-top: 1rem;">
                <i class="bi bi-save-fill"></i> Save Profile Changes
            </button>
        </form>
    </main>

</body>
</html>
