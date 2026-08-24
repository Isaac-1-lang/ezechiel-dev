<?php
/**
 * Utility Functions & i18n Translation Engine
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';

// Set active language (Default: 'en')
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fr', 'es', 'sw'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// Load language translation file
$lang_file = __DIR__ . '/../languages/' . $current_lang . '.php';
if (!file_exists($lang_file)) {
    $lang_file = __DIR__ . '/../languages/en.php';
}
$translations = require $lang_file;

/**
 * Translate key helper function
 */
function __t($key, $default = '') {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : ($default !== '' ? $default : $key);
}

/**
 * Sanitize string input for output safety
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF Token Generator
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Fetch Developer Profile
 */
function getProfileData() {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM profile WHERE id = 1 LIMIT 1");
        $profile = $stmt->fetch();
        if ($profile) {
            return $profile;
        }
    } catch (Exception $e) {
        // Fallback array if DB error
    }
    return [
        'full_name' => 'Ezechiel Developer',
        'title' => 'Senior Full-Stack Engineer & Global Software Architect',
        'tagline' => 'Building high-impact web apps, distributed systems & cloud solutions for global clients.',
        'bio' => 'I am a passionate Full-Stack Software Developer dedicated to solving complex problems through clean code, modern software design, and scalable databases.',
        'life_story' => 'My journey into software engineering started with a relentless curiosity about how computer systems work under the hood. Growing up fascinated by global technology trends, I taught myself web development, database architecture, and server management.',
        'philosophy' => 'I believe code is an international language for human empowerment.',
        'location' => 'Kigali, Rwanda',
        'country' => 'Rwanda',
        'latitude' => -1.9441,
        'longitude' => 30.0619,
        'timezone' => 'Africa/Kigali',
        'availability' => '🟢 Open for Global Remote & Contract Roles',
        'email' => 'dev.ezechiel@example.com',
        'phone' => '+250 780 000 000',
        'github' => 'https://github.com',
        'linkedin' => 'https://linkedin.com',
        'twitter' => 'https://twitter.com',
        'whatsapp' => '+250780000000',
        'resume_url' => '#',
        'profile_image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80'
    ];
}

/**
 * Check if Admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require Admin Login
 */
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}
