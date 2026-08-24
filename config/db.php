<?php
/**
 * Database Connection & Configuration Bridge
 * Standard MySQL PDO connector with auto-table initialization and SQLite fallback capability.
 */

// Enable strict error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
if (!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT') ?: '3306');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: '');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'portfolio_db');

class Database {
    private static $instance = null;
    private $pdo;
    private $driver = 'mysql';

    private function __construct() {
        $dbDir = __DIR__ . '/../database';
        if (!file_exists($dbDir)) {
            @mkdir($dbDir, 0777, true);
        }

        try {
            // Attempt 1: Connect to MySQL Server
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 3
            ];

            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            // Create database if not exists
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->pdo->exec("USE `" . DB_NAME . "`");
            $this->driver = 'mysql';

        } catch (PDOException $e) {
            // Attempt 2: Fallback to SQLite so the application works out-of-the-box in any PHP server environment
            try {
                $sqliteFile = $dbDir . '/portfolio.sqlite';
                $this->pdo = new PDO("sqlite:" . $sqliteFile);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->driver = 'sqlite';
            } catch (PDOException $ex) {
                die("Database connection failed: " . $ex->getMessage());
            }
        }

        // Initialize schema if tables don't exist
        $this->autoInitializeSchema();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }

    public static function getDriver() {
        return self::$instance ? self::$instance->driver : 'mysql';
    }

    private function autoInitializeSchema() {
        try {
            // Check if admin_users table exists
            if ($this->driver === 'mysql') {
                $stmt = $this->pdo->query("SHOW TABLES LIKE 'admin_users'");
                $hasTables = $stmt->rowCount() > 0;
            } else {
                $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admin_users'");
                $hasTables = $stmt->fetch() !== false;
            }

            if (!$hasTables) {
                if ($this->driver === 'mysql') {
                    $sqlFile = __DIR__ . '/../database/schema.sql';
                    if (file_exists($sqlFile)) {
                        $sql = file_get_contents($sqlFile);
                        $this->pdo->exec($sql);
                    }
                } else {
                    // SQLite table initialization
                    $this->pdo->exec("
                        CREATE TABLE IF NOT EXISTS admin_users (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            username TEXT UNIQUE NOT NULL,
                            password_hash TEXT NOT NULL,
                            email TEXT NOT NULL,
                            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                        );

                        CREATE TABLE IF NOT EXISTS profile (
                            id INTEGER PRIMARY KEY,
                            full_name TEXT NOT NULL,
                            title TEXT NOT NULL,
                            tagline TEXT NOT NULL,
                            bio TEXT NOT NULL,
                            life_story TEXT NOT NULL,
                            philosophy TEXT NOT NULL,
                            location TEXT NOT NULL,
                            country TEXT NOT NULL,
                            latitude REAL DEFAULT -1.9441,
                            longitude REAL DEFAULT 30.0619,
                            timezone TEXT DEFAULT 'Africa/Kigali',
                            availability TEXT DEFAULT 'Open for Global Remote Work',
                            email TEXT NOT NULL,
                            phone TEXT,
                            github TEXT,
                            linkedin TEXT,
                            twitter TEXT,
                            whatsapp TEXT,
                            resume_url TEXT,
                            profile_image TEXT,
                            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                        );

                        CREATE TABLE IF NOT EXISTS projects (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            title TEXT NOT NULL,
                            category TEXT NOT NULL,
                            summary TEXT NOT NULL,
                            description TEXT NOT NULL,
                            tech_stack TEXT NOT NULL,
                            image_url TEXT NOT NULL,
                            demo_link TEXT,
                            github_link TEXT,
                            is_featured INTEGER DEFAULT 0,
                            sort_order INTEGER DEFAULT 0,
                            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                        );

                        CREATE TABLE IF NOT EXISTS skills (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            category TEXT NOT NULL,
                            name TEXT NOT NULL,
                            proficiency_percent INTEGER DEFAULT 85,
                            icon_class TEXT DEFAULT 'bi bi-code-slash',
                            sort_order INTEGER DEFAULT 0
                        );

                        CREATE TABLE IF NOT EXISTS experiences (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            type TEXT DEFAULT 'work',
                            role_title TEXT NOT NULL,
                            organization TEXT NOT NULL,
                            location TEXT,
                            start_date TEXT NOT NULL,
                            end_date TEXT DEFAULT 'Present',
                            is_current INTEGER DEFAULT 0,
                            description TEXT NOT NULL,
                            sort_order INTEGER DEFAULT 0
                        );

                        CREATE TABLE IF NOT EXISTS services (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            title TEXT NOT NULL,
                            description TEXT NOT NULL,
                            icon TEXT DEFAULT 'bi bi-laptop',
                            features TEXT,
                            sort_order INTEGER DEFAULT 0
                        );

                        CREATE TABLE IF NOT EXISTS testimonials (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            client_name TEXT NOT NULL,
                            client_role TEXT NOT NULL,
                            company TEXT NOT NULL,
                            country TEXT DEFAULT 'United States',
                            flag_code TEXT DEFAULT 'us',
                            rating INTEGER DEFAULT 5,
                            message TEXT NOT NULL,
                            avatar_url TEXT,
                            sort_order INTEGER DEFAULT 0
                        );

                        CREATE TABLE IF NOT EXISTS contact_messages (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            sender_name TEXT NOT NULL,
                            sender_email TEXT NOT NULL,
                            subject TEXT,
                            message TEXT NOT NULL,
                            is_read INTEGER DEFAULT 0,
                            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                        );
                    ");

                    // Seed default SQLite data if empty
                    $hash = password_hash('admin123', PASSWORD_DEFAULT);
                    $this->pdo->exec("INSERT OR IGNORE INTO admin_users (id, username, password_hash, email) VALUES (1, 'admin', '$hash', 'admin@example.com');");

                    $this->pdo->exec("INSERT OR IGNORE INTO profile (id, full_name, title, tagline, bio, life_story, philosophy, location, country, latitude, longitude, timezone, availability, email, phone, github, linkedin, twitter, whatsapp, resume_url, profile_image) VALUES (
                        1, 'Ezechiel Developer', 'Senior Full-Stack Engineer & Global Software Architect',
                        'Building high-impact web apps, distributed systems & cloud solutions for global clients.',
                        'I am a passionate Full-Stack Software Developer dedicated to solving complex problems through clean code, modern software design, and scalable databases.',
                        'My journey into software engineering started with a relentless curiosity about how computer systems work under the hood. Growing up fascinated by global technology trends, I taught myself web development, database architecture, and server management. Over the years, I have worked with international teams, startups, and enterprises worldwide, crafting elegant software that bridges cultures and transforms ideas into reality.',
                        'I believe code is not just instructions for computers, but an international language for human empowerment. My philosophy centers on continuous learning, empathy-driven product engineering, and uncompromising code quality.',
                        'Kigali, Rwanda', 'Rwanda', -1.94410000, 30.06190000, 'Africa/Kigali',
                        '🟢 Open for Global Remote & Contract Roles', 'dev.ezechiel@example.com', '+250 780 000 000',
                        'https://github.com', 'https://linkedin.com', 'https://twitter.com', '+250780000000', '#',
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80'
                    );");

                    $this->pdo->exec("INSERT OR IGNORE INTO projects (id, title, category, summary, description, tech_stack, image_url, demo_link, github_link, is_featured, sort_order) VALUES
                        (1, 'CineStream Global Streaming', 'Web Apps', 'Full-stack video streaming, advertising & download platform.', 'A high-performance web platform for showcasing movies, online HTTP range streaming video player, direct local download engine, and real-time MySQL analytics dashboard.', 'PHP, MySQL, JavaScript, HTML5, CSS3', 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?auto=format&fit=crop&w=600&q=80', '#', '#', 1, 1),
                        (2, 'Nexus Cloud SaaS Engine', 'Cloud & APIs', 'Multi-tenant cloud management platform with real-time telemetry.', 'A cloud platform allowing enterprises to monitor distributed container deployments, manage microservices, and analyze live database query metrics.', 'PHP, PDO, REST API, MySQL, Redis, Docker', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80', '#', '#', 1, 2),
                        (3, 'GlobalPay Cross-Border Gateway', 'Fintech', 'Secure multi-currency payment orchestration API.', 'Fintech gateway facilitating cross-border settlements with localized payment rails, instant webhook notifications, and PCI-DSS compliant encryption.', 'PHP 8, MySQL, REST APIs, Webhooks, Vue.js', 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=600&q=80', '#', '#', 1, 3);");

                    $this->pdo->exec("INSERT OR IGNORE INTO skills (id, category, name, proficiency_percent, icon_class, sort_order) VALUES
                        (1, 'Backend', 'PHP 8 & PDO', 95, 'bi bi-filetype-php', 1),
                        (2, 'Backend', 'MySQL & Database Architecture', 92, 'bi bi-database-gear', 2),
                        (3, 'Frontend', 'JavaScript (ES6+) & Modern Web APIs', 90, 'bi bi-filetype-js', 3),
                        (4, 'Frontend', 'HTML5, CSS3 & Responsive UI Design', 95, 'bi bi-filetype-html', 4),
                        (5, 'Backend', 'RESTful API & Microservices', 88, 'bi bi-diagram-3', 5),
                        (6, 'DevOps', 'Git, GitHub & CI/CD Pipelines', 85, 'bi bi-git', 6);");

                    $this->pdo->exec("INSERT OR IGNORE INTO experiences (id, type, role_title, organization, location, start_date, end_date, is_current, description, sort_order) VALUES
                        (1, 'work', 'Senior Full-Stack Developer', 'International Tech Solutions', 'Global Remote', '2023', 'Present', 1, 'Leading cross-functional software architecture for web apps, MySQL database optimization, and high-load RESTful APIs serving thousands of international users.', 1),
                        (2, 'work', 'Full-Stack Software Engineer', 'Kigali Digital Hub', 'Kigali, Rwanda', '2021', '2023', 0, 'Engineered custom PHP/MySQL web management systems, payment gateway integrations, and client management dashboards with responsive UI components.', 2);");

                    $this->pdo->exec("INSERT OR IGNORE INTO services (id, title, description, icon, features, sort_order) VALUES
                        (1, 'Custom Full-Stack Web Development', 'Building tailored, scalable web applications from scratch using robust PHP backend engines and modern dynamic frontend interfaces.', 'bi bi-laptop', 'PHP 8+, MySQL DB, Dynamic Dashboards, Security Best Practices', 1),
                        (2, 'REST API & Database Design', 'Architecting high-performance SQL schemas, database migrations, and RESTful APIs for seamless third-party integrations.', 'bi bi-diagram-3-fill', 'Relational Schemas, PDO Security, JSON API Endpoints, Query Optimization', 2);");

                    $this->pdo->exec("INSERT OR IGNORE INTO testimonials (id, client_name, client_role, company, country, flag_code, rating, message, avatar_url, sort_order) VALUES
                        (1, 'Sarah Jenkins', 'VP of Engineering', 'NovaTech Global', 'United States', 'us', 5, 'Ezechiel is an exceptional developer! His mastery of PHP and MySQL coupled with his keen eye for modern design delivered our portal ahead of schedule.', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=200&q=80', 1);");
                }
            }
        } catch (Exception $e) {
            // Silent catch to prevent script halt
        }
    }
}

// Function helper to get active PDO connection instance
function getDB() {
    return Database::getInstance();
}
