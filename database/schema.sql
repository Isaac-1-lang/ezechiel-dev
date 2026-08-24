-- International Developer Portfolio Database Schema
-- Compatible with MySQL 5.7+, MySQL 8.0+, and MariaDB

CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

-- 1. Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Developer Profile & Life Info
CREATE TABLE IF NOT EXISTS profile (
    id INT PRIMARY KEY DEFAULT 1,
    full_name VARCHAR(100) NOT NULL,
    title VARCHAR(150) NOT NULL,
    tagline VARCHAR(255) NOT NULL,
    bio TEXT NOT NULL,
    life_story TEXT NOT NULL,
    philosophy TEXT NOT NULL,
    location VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    latitude DECIMAL(10, 8) DEFAULT -1.9441,
    longitude DECIMAL(11, 8) DEFAULT 30.0619,
    timezone VARCHAR(50) DEFAULT 'Africa/Kigali',
    availability VARCHAR(100) DEFAULT 'Available for Remote & Contract Work',
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    github VARCHAR(150),
    linkedin VARCHAR(150),
    twitter VARCHAR(150),
    whatsapp VARCHAR(50),
    resume_url VARCHAR(255),
    profile_image VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Projects Showcase
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'Web Apps',
    summary VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    tech_stack VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    demo_link VARCHAR(255),
    github_link VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Skills Matrix
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL DEFAULT 'Frontend',
    name VARCHAR(80) NOT NULL,
    proficiency_percent INT NOT NULL DEFAULT 85,
    icon_class VARCHAR(50) DEFAULT 'bi bi-code-slash',
    sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Career & Education Timeline
CREATE TABLE IF NOT EXISTS experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('work', 'education') DEFAULT 'work',
    role_title VARCHAR(120) NOT NULL,
    organization VARCHAR(120) NOT NULL,
    location VARCHAR(100),
    start_date VARCHAR(30) NOT NULL,
    end_date VARCHAR(30) DEFAULT 'Present',
    is_current TINYINT(1) DEFAULT 0,
    description TEXT NOT NULL,
    sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Services Offered
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(50) DEFAULT 'bi bi-laptop',
    features TEXT,
    sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Testimonials & Recommendations
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_role VARCHAR(100) NOT NULL,
    company VARCHAR(100) NOT NULL,
    country VARCHAR(50) DEFAULT 'United States',
    flag_code VARCHAR(10) DEFAULT 'us',
    rating INT DEFAULT 5,
    message TEXT NOT NULL,
    avatar_url VARCHAR(255),
    sort_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Contact Messages Inbox
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_name VARCHAR(100) NOT NULL,
    sender_email VARCHAR(100) NOT NULL,
    subject VARCHAR(150),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED INITIAL DEFAULT DATA --

-- Default Admin Account (Username: admin, Password: admin123)
INSERT INTO admin_users (username, password_hash, email) 
VALUES ('admin', '$2y$10$eE61K1k1Jc793Hk3.iP02eZqXp1KkYyW8s7.q7H8J8Z.u7N7Q7U6K', 'admin@example.com')
ON DUPLICATE KEY UPDATE id=id;

-- Default Profile Info
INSERT INTO profile (id, full_name, title, tagline, bio, life_story, philosophy, location, country, latitude, longitude, timezone, availability, email, phone, github, linkedin, twitter, whatsapp, resume_url, profile_image)
VALUES (
    1,
    'Ezechiel Developer',
    'Senior Full-Stack Engineer & Global Software Architect',
    'Building high-impact web apps, distributed systems & cloud solutions for global clients.',
    'I am a passionate Full-Stack Software Developer dedicated to solving complex problems through clean code, modern software design, and scalable databases.',
    'My journey into software engineering started with a relentless curiosity about how computer systems work under the hood. Growing up fascinated by global technology trends, I taught myself web development, database architecture, and server management. Over the years, I have worked with international teams, startups, and enterprises worldwide, crafting elegant software that bridges cultures and transforms ideas into reality.',
    'I believe code is not just instructions for computers, but an international language for human empowerment. My philosophy centers on continuous learning, empathy-driven product engineering, and uncompromising code quality.',
    'Kigali, Rwanda',
    'Rwanda',
    -1.94410000,
    30.06190000,
    'Africa/Kigali',
    '🟢 Open for Global Remote & Contract Roles',
    'dev.ezechiel@example.com',
    '+250 780 000 000',
    'https://github.com',
    'https://linkedin.com',
    'https://twitter.com',
    '+250780000000',
    '#',
    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80'
)
ON DUPLICATE KEY UPDATE id=1;

-- Default Projects
INSERT INTO projects (title, category, summary, description, tech_stack, image_url, demo_link, github_link, is_featured, sort_order) VALUES
('CineStream Global Streaming', 'Web Apps', 'Full-stack video streaming, advertising & download platform.', 'A high-performance web platform for showcasing movies, online HTTP range streaming video player, direct local download engine, and real-time MySQL analytics dashboard.', 'PHP, MySQL, JavaScript, HTML5, CSS3', 'https://images.unsplash.com/photo-1574375927938-d5a98e8ffe85?auto=format&fit=crop&w=600&q=80', '#', '#', 1, 1),
('Nexus Cloud SaaS Engine', 'Cloud & APIs', 'Multi-tenant cloud management platform with real-time telemetry.', 'A cloud platform allowing enterprises to monitor distributed container deployments, manage microservices, and analyze live database query metrics.', 'PHP, PDO, REST API, MySQL, Redis, Docker', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80', '#', '#', 1, 2),
('GlobalPay Cross-Border Gateway', 'Fintech', 'Secure multi-currency payment orchestration API.', 'Fintech gateway facilitating cross-border settlements with localized payment rails, instant webhook notifications, and PCI-DSS compliant encryption.', 'PHP 8, MySQL, REST APIs, Webhooks, Vue.js', 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=600&q=80', '#', '#', 1, 3),
('AgriTech Supply Chain Tracker', 'Mobile Apps', 'Real-time agricultural logistics and inventory management system.', 'Mobile-first platform tracking produce from African farms to global export hubs with QR code verification and offline sync capabilities.', 'JavaScript, PHP, MySQL, Leaflet.js, PWA', 'https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&w=600&q=80', '#', '#', 0, 4);

-- Default Skills
INSERT INTO skills (category, name, proficiency_percent, icon_class, sort_order) VALUES
('Backend', 'PHP 8 & PDO', 95, 'bi bi-filetype-php', 1),
('Backend', 'MySQL & Database Architecture', 92, 'bi bi-database-gear', 2),
('Frontend', 'JavaScript (ES6+) & Modern Web APIs', 90, 'bi bi-filetype-js', 3),
('Frontend', 'HTML5, CSS3 & Responsive UI Design', 95, 'bi bi-filetype-html', 4),
('Backend', 'RESTful API & Microservices', 88, 'bi bi-diagram-3', 5),
('DevOps', 'Git, GitHub & CI/CD Pipelines', 85, 'bi bi-git', 6),
('DevOps', 'Linux Server Admin & Nginx/Apache', 82, 'bi bi-terminal', 7),
('Cloud', 'Cloud Hosting & Containerization', 80, 'bi bi-cloud-check', 8);

-- Default Experiences
INSERT INTO experiences (type, role_title, organization, location, start_date, end_date, is_current, description, sort_order) VALUES
('work', 'Senior Full-Stack Developer', 'International Tech Solutions', 'Global Remote', '2023', 'Present', 1, 'Leading cross-functional software architecture for web apps, MySQL database optimization, and high-load RESTful APIs serving thousands of international users.', 1),
('work', 'Full-Stack Software Engineer', 'Kigali Digital Hub', 'Kigali, Rwanda', '2021', '2023', 0, 'Engineered custom PHP/MySQL web management systems, payment gateway integrations, and client management dashboards with responsive UI components.', 2),
('education', 'B.Sc. in Computer Science & Software Engineering', 'University of Technology', 'Rwanda', '2017', '2021', 0, 'Graduated with Distinction. Focused on Algorithm Analysis, Relational Database Management Systems (RDBMS), Web Technologies, and Network Security.', 3);

-- Default Services
INSERT INTO services (title, description, icon, features, sort_order) VALUES
('Custom Full-Stack Web Development', 'Building tailored, scalable web applications from scratch using robust PHP backend engines and modern dynamic frontend interfaces.', 'bi bi-laptop', 'PHP 8+, MySQL DB, Dynamic Dashboards, Security Best Practices', 1),
('REST API & Database Design', 'Architecting high-performance SQL schemas, database migrations, and RESTful APIs for seamless third-party integrations.', 'bi bi-diagram-3-fill', 'Relational Schemas, PDO Security, JSON API Endpoints, Query Optimization', 2),
('International Portfolio & Web Solutions', 'Crafting custom international web solutions with multi-language support (i18n), dark mode aesthetics, and responsive performance.', 'bi bi-globe2', 'Multi-Language i18n, Glassmorphic UI, Timezone integration, Interactive Maps', 3);

-- Default Testimonials
INSERT INTO testimonials (client_name, client_role, company, country, flag_code, rating, message, avatar_url, sort_order) VALUES
('Sarah Jenkins', 'VP of Engineering', 'NovaTech Global', 'United States', 'us', 5, 'Ezechiel is an exceptional developer! His mastery of PHP and MySQL coupled with his keen eye for modern design delivered our portal ahead of schedule.', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=200&q=80', 1),
('Marc Dubois', 'Product Manager', 'AfriCloud Solutions', 'France / Rwanda', 'fr', 5, 'Working with Ezechiel on our enterprise application was a flawless experience. Excellent communication, robust backend architecture, and great execution.', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80', 2);
