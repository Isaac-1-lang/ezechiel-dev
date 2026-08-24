<?php
require_once __DIR__ . '/functions.php';
$profile = getProfileData();
?>
<!DOCTYPE html>
<html lang="<?php echo sanitize($current_lang); ?>" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($profile['full_name']); ?> | <?php echo sanitize($profile['title']); ?></title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo sanitize($profile['tagline']); ?>">
    <meta name="keywords" content="Full-Stack Developer, PHP Developer, MySQL Architect, Software Engineer, Kigali Rwanda Developer, Global Remote Software Developer">
    <meta name="author" content="<?php echo sanitize($profile['full_name']); ?>">

    <!-- Leaflet CSS for Interactive Location Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Application CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Ambient Glowing Background Elements -->
    <div class="ambient-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Navigation Header -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="brand-logo">
                <div class="logo-icon"><i class="bi bi-code-slash"></i></div>
                <span><?php echo sanitize($profile['full_name']); ?></span>
            </a>

            <ul class="nav-links">
                <li><a href="#home"><?php echo __t('nav_home'); ?></a></li>
                <li><a href="#about"><?php echo __t('nav_about'); ?></a></li>
                <li><a href="#location"><?php echo __t('nav_location'); ?></a></li>
                <li><a href="#projects"><?php echo __t('nav_projects'); ?></a></li>
                <li><a href="#skills"><?php echo __t('nav_skills'); ?></a></li>
                <li><a href="#experience"><?php echo __t('nav_experience'); ?></a></li>
                <li><a href="#services"><?php echo __t('nav_services'); ?></a></li>
                <li><a href="#contact"><?php echo __t('nav_contact'); ?></a></li>
            </ul>

            <div class="nav-actions">
                <!-- Theme Mode Toggle Button -->
                <button type="button" class="theme-toggle-btn" id="themeToggleBtn" title="Toggle Theme">
                    <i class="bi bi-sun-fill"></i>
                </button>

                <!-- Language Selector Dropdown -->
                <div class="lang-selector">
                    <select class="lang-select" onchange="location = this.value;">
                        <option value="?lang=en" <?php echo $current_lang === 'en' ? 'selected' : ''; ?>>🇺🇸 EN</option>
                        <option value="?lang=fr" <?php echo $current_lang === 'fr' ? 'selected' : ''; ?>>🇫🇷 FR</option>
                        <option value="?lang=es" <?php echo $current_lang === 'es' ? 'selected' : ''; ?>>🇪🇸 ES</option>
                        <option value="?lang=sw" <?php echo $current_lang === 'sw' ? 'selected' : ''; ?>>🇪🇭 SW</option>
                    </select>
                </div>

                <a href="admin/login.php" class="btn btn-outline" style="padding: 0.4rem 0.9rem; font-size: 0.85rem;" title="Admin Dashboard">
                    <i class="bi bi-lock-fill"></i> <?php echo __t('nav_admin'); ?>
                </a>
            </div>
        </div>
    </nav>
