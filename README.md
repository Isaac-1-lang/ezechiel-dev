# 🌐 International Developer Portfolio (PHP & MySQL)

A modern, high-performance, international developer portfolio website powered by **PHP** and **MySQL**. Built with state-of-the-art glassmorphism design aesthetics, dark/light mode, dynamic Leaflet location map, synchronized world clocks, multi-language internationalization (English, French, Spanish, Swahili), and a **Full Admin CMS Panel**.

---

## ✨ Key Features

1. **Who I Am & Life Story (About Section)**:
   - Dynamic Hero banner with developer title, availability badge ("🟢 Open for Global Remote Work"), and local timezone clock.
   - Comprehensive "About Me" section showcasing life history, developer journey, personal philosophy, global values, and language proficiencies (English, French, Swahili, Kinyarwanda).
   - Interactive hobby & personal interest cards.

2. **Interactive Location & World Clock**:
   - Interactive Leaflet.js map centered on developer location (Kigali, Rwanda / Global).
   - Synchronized world clock comparing developer local time with visitor computer time.

3. **Full Portfolio Features**:
   - **Filterable Projects Showcase**: Categorized grid (Web Apps, Cloud & APIs, Fintech, Mobile Apps) with live demo links, GitHub links, tech badges, and modal case study viewer.
   - **Skills & Tech Stack Matrix**: Visual progress bars and tech icons grouped by Frontend, Backend, DevOps, and Cloud.
   - **Experience & Education Timeline**: Interactive vertical timeline detailing career growth, companies, roles, and degrees.
   - **Services Offered**: Full-stack web development, API architecture, database engineering.
   - **Testimonials & Recommendations**: Dynamic client quote cards with flag indicators, ratings, and avatars.
   - **Contact Section & Direct Messaging**: Working AJAX-powered PHP contact form storing incoming messages in MySQL.

4. **Multi-Language Support (i18n)**:
   - Built-in multi-language translation engine supporting **English (EN)**, **French (FR)**, **Spanish (ES)**, and **Swahili (SW)**.

5. **Secure Admin CMS Dashboard (`/admin`)**:
   - Protected admin authentication login (`admin` / `admin123`).
   - Management tabs for Profile & Life Story, Projects Manager (CRUD), Skills Matrix (CRUD), Career Timeline (CRUD), Inbox Messages Manager, Services & Testimonials.

6. **Dual Database Bridge (MySQL + SQLite Fallback)**:
   - Seamless MySQL PDO connector for local database (`portfolio_db`).
   - Auto-creates database schema and seeds default data on initial boot.
   - SQLite fallback support so the app functions 100% out of the box in any PHP environment.

---

## 📁 File Structure

```
├── config/
│   └── db.php                # Database PDO connection (MySQL + SQLite fallback)
├── database/
│   └── schema.sql            # Full MySQL database creation & seed script
├── languages/
│   ├── en.php                # English translations
│   ├── fr.php                # French translations
│   ├── es.php                # Spanish translations
│   └── sw.php                # Swahili translations
├── includes/
│   ├── functions.php         # Utility helpers, i18n translation engine, sanitize
│   ├── header.php            # Main site navbar & header layout
│   └── footer.php            # Main site footer layout
├── admin/
│   ├── index.php             # Admin Dashboard Overview
│   ├── login.php             # Admin Authentication Login
│   ├── logout.php            # Admin Logout handler
│   ├── profile.php           # Admin Profile & Life Story Editor
│   ├── projects.php          # Admin Projects CRUD
│   ├── skills.php            # Admin Skills CRUD
│   ├── experience.php        # Admin Career Timeline CRUD
│   ├── messages.php          # Admin Contact Inbox Manager
│   └── services.php          # Admin Services & Testimonials CRUD
├── assets/
│   ├── css/
│   │   ├── style.css         # Glassmorphism & Responsive CSS
│   │   └── admin.css         # Admin Panel Dashboard CSS
│   └── js/
│       ├── main.js           # Interactive UI, Theme Toggle, Timezone clock, Map
│       └── contact.js        # AJAX contact form submit handler
├── api/
│   └── contact.php           # Contact form POST processing API
├── setup.php                 # Interactive database initializer script
├── index.php                 # Public Portfolio Front Page
└── README.md                 # Project documentation
```

---

## 🚀 How to Run the Application

### Option A: Running with PHP Built-in Server (Recommended for Instant Testing)

Open your terminal or command prompt in the project directory and run:

```bash
php -S localhost:8000
```

Then visit in your browser:
- **Public Portfolio**: `http://localhost:8000`
- **Admin Dashboard**: `http://localhost:8000/admin`
- **Database Setup Utility**: `http://localhost:8000/setup.php`

### Option B: Running with XAMPP / WAMP / Apache & MySQL

1. Copy the project folder `ezechie` into your XAMPP `htdocs` directory (e.g. `C:\xampp\htdocs\portfolio`).
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
3. Import `database/schema.sql` or visit `http://localhost/portfolio/setup.php`.
4. Open your browser and navigate to `http://localhost/portfolio`.

---

## 🔑 Admin Dashboard Login Credentials

- **URL**: `http://localhost:8000/admin/login.php`
- **Username**: `admin`
- **Password**: `admin123`

*(Note: Password can be changed anytime from the Admin Profile tab)*
# ezechiel-dev
