<?php
/**
 * International Developer Portfolio Main Entry Point
 */

require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Fetch Data from MySQL/Database
$projectsStmt = $db->query("SELECT * FROM projects ORDER BY sort_order ASC, id DESC");
$projects = $projectsStmt->fetchAll();

$skillsStmt = $db->query("SELECT * FROM skills ORDER BY category ASC, sort_order ASC");
$skills = $skillsStmt->fetchAll();

$experiencesStmt = $db->query("SELECT * FROM experiences ORDER BY sort_order ASC, id DESC");
$experiences = $experiencesStmt->fetchAll();

$servicesStmt = $db->query("SELECT * FROM services ORDER BY sort_order ASC");
$services = $servicesStmt->fetchAll();

$testimonialsStmt = $db->query("SELECT * FROM testimonials ORDER BY sort_order ASC");
$testimonials = $testimonialsStmt->fetchAll();
?>

<!-- 1. Hero Section -->
<section id="home" class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="status-badge">
                    <div class="status-dot"></div>
                    <span><?php echo sanitize($profile['availability']); ?></span>
                </div>

                <h1 class="hero-title">
                    <?php echo __t('hero_greeting'); ?> <br>
                    <span class="gradient-text"><?php echo sanitize($profile['full_name']); ?></span>
                </h1>

                <h3 style="color: var(--accent-indigo); font-weight: 600; margin-bottom: 1rem;">
                    <?php echo sanitize($profile['title']); ?>
                </h3>

                <p class="hero-tagline">
                    <?php echo sanitize($profile['tagline']); ?>
                </p>

                <div class="hero-actions">
                    <a href="#contact" class="btn btn-primary">
                        
                        <i class="bi bi-send-fill"></i> <?php echo __t('hero_cta_hire'); ?>
                    </a>
                    <a href="#projects" class="btn btn-outline">
                        <i class="bi bi-grid-fill"></i> <?php echo __t('hero_cta_projects'); ?>
                    </a>
                    <?php if (!empty($profile['resume_url']) && $profile['resume_url'] !== '#'): ?>
                        <a href="<?php echo sanitize($profile['resume_url']); ?>" class="btn btn-outline" target="_blank">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> <?php echo __t('hero_cta_resume'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hero-card-wrapper">
                <div class="glass-card hero-profile-card">
                    <div class="profile-img-container">
                        <img src="<?php echo sanitize($profile['profile_image']); ?>" alt="<?php echo sanitize($profile['full_name']); ?>" class="profile-img">
                        <div class="tech-badge-pill" title="PHP & MySQL Stack">
                            <i class="bi bi-database-fill-gear"></i>
                        </div>
                    </div>

                    <h3><?php echo sanitize($profile['full_name']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                        <i class="bi bi-geo-alt-fill" style="color: var(--accent-indigo);"></i> <?php echo sanitize($profile['location']); ?>
                    </p>

                    <div style="display: flex; justify-content: center; gap: 0.8rem; font-size: 0.85rem; color: var(--accent-cyan); background: var(--bg-glass); padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                        <span><i class="bi bi-globe"></i> International Remote</span>
                        <span>•</span>
                        <span><i class="bi bi-clock-history"></i> <?php echo sanitize($profile['timezone']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. About Me & Life Story Section -->
<section id="about" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('nav_about'); ?></span>
            <h2 class="section-title"><?php echo __t('about_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('about_subtitle'); ?></p>
        </div>

        <div class="about-tabs">
            <button class="tab-btn active" data-tab="tab-bio"><?php echo __t('about_tab_bio'); ?></button>
            <button class="tab-btn" data-tab="tab-story"><?php echo __t('about_tab_story'); ?></button>
            <button class="tab-btn" data-tab="tab-philosophy"><?php echo __t('about_tab_philosophy'); ?></button>
        </div>

        <div class="glass-card">
            <!-- Bio Tab -->
            <div id="tab-bio" class="tab-content active">
                <div class="about-grid">
                    <div>
                        <h3 style="margin-bottom: 1rem; color: var(--accent-indigo);">Full-Stack Engineering & System Design</h3>
                        <p style="font-size: 1.05rem; line-height: 1.8; margin-bottom: 1.25rem;">
                            <?php echo nl2br(sanitize($profile['bio'])); ?>
                        </p>
                    </div>
                    <div style="background: var(--bg-glass); border: 1px solid var(--border-color); padding: 1.5rem; border-radius: var(--radius-md);">
                        <h4 style="margin-bottom: 1rem;"><i class="bi bi-translate" style="color: var(--accent-cyan);"></i> Spoken Languages</h4>
                        <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem; font-weight: 500;">
                            <li style="display: flex; justify-content: space-between;"><span>🇬🇧 English</span> <strong style="color: var(--accent-emerald);">Fluent (Professional)</strong></li>
                            <li style="display: flex; justify-content: space-between;"><span>🇫🇷 French</span> <strong style="color: var(--accent-emerald);">Advanced</strong></li>
                            <li style="display: flex; justify-content: space-between;"><span>🇰🇪 Swahili</span> <strong style="color: var(--accent-emerald);">Native / Fluent</strong></li>
                            <li style="display: flex; justify-content: space-between;"><span>🇷🇼 Kinyarwanda</span> <strong style="color: var(--accent-emerald);">Native</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Life Story Tab -->
            <div id="tab-story" class="tab-content">
                <h3 style="margin-bottom: 1rem; color: var(--accent-purple);">My Journey & Background</h3>
                <p style="font-size: 1.05rem; line-height: 1.8;">
                    <?php echo nl2br(sanitize($profile['life_story'])); ?>
                </p>
            </div>

            <!-- Philosophy Tab -->
            <div id="tab-philosophy" class="tab-content">
                <h3 style="margin-bottom: 1rem; color: var(--accent-cyan);">Core Principles & Global Vision</h3>
                <p style="font-size: 1.05rem; line-height: 1.8;">
                    <?php echo nl2br(sanitize($profile['philosophy'])); ?>
                </p>
            </div>

            <!-- Hobbies Grid -->
            <div style="margin-top: 2.5rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
                <h4 style="text-align: center; margin-bottom: 1rem; color: var(--text-heading);"><?php echo __t('about_hobbies_title'); ?></h4>
                <div class="hobby-grid">
                    <div class="hobby-card">
                        <i class="bi bi-cpu-fill"></i>
                        <h5>System Architecture</h5>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Designing scalable databases & backend engines</p>
                    </div>
                    <div class="hobby-card">
                        <i class="bi bi-airplane-engines-fill"></i>
                        <h5>Global Travel</h5>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Exploring diverse cultures and perspectives</p>
                    </div>
                    <div class="hobby-card">
                        <i class="bi bi-journal-code"></i>
                        <h5>Open Source</h5>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Contributing code & technical blogging</p>
                    </div>
                    <div class="hobby-card">
                        <i class="bi bi-people-fill"></i>
                        <h5>Tech Mentorship</h5>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Empowering the next generation of engineers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Location & Timezone Section -->
<section id="location" class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('nav_location'); ?></span>
            <h2 class="section-title"><?php echo __t('location_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('location_subtitle'); ?></p>
        </div>

        <div class="location-grid">
            <div class="glass-card" style="padding: 1rem;">
                <div id="map" 
                     data-lat="<?php echo sanitize($profile['latitude']); ?>" 
                     data-lng="<?php echo sanitize($profile['longitude']); ?>" 
                     data-location="<?php echo sanitize($profile['location']); ?>">
                </div>
            </div>

            <div class="glass-card clock-card">
                <h3 style="text-align: center; margin-bottom: 1rem;"><i class="bi bi-clock" style="color: var(--accent-indigo);"></i> Real-Time Synchronized Clocks</h3>

                <div class="time-box">
                    <span style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;"><?php echo __t('local_time'); ?> (<?php echo sanitize($profile['location']); ?>)</span>
                    <div id="devLocalTime" class="time-display">--:--:--</div>
                </div>

                <div class="time-box">
                    <span style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;"><?php echo __t('visitor_time'); ?> (Your Computer)</span>
                    <div id="visitorLocalTime" class="time-display" style="color: var(--accent-purple);">--:--:--</div>
                </div>

                <div style="background: var(--bg-glass); border: 1px solid var(--border-color); padding: 1rem; border-radius: var(--radius-md); text-align: center;">
                    <span style="display: block; font-size: 0.85rem; color: var(--text-muted);"><?php echo __t('work_availability'); ?></span>
                    <strong style="color: var(--accent-emerald); font-size: 1rem;"><?php echo sanitize($profile['availability']); ?></strong>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Projects Showcase Section -->
<section id="projects" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('nav_projects'); ?></span>
            <h2 class="section-title"><?php echo __t('projects_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('projects_subtitle'); ?></p>
        </div>

        <!-- Filter Buttons -->
        <div class="project-filters">
            <button class="filter-btn active" data-filter="all"><?php echo __t('project_filter_all'); ?></button>
            <button class="filter-btn" data-filter="Web Apps">Web Apps</button>
            <button class="filter-btn" data-filter="Cloud & APIs">Cloud & APIs</button>
            <button class="filter-btn" data-filter="Fintech">Fintech</button>
            <button class="filter-btn" data-filter="Mobile Apps">Mobile Apps</button>
        </div>

        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="glass-card project-card" 
                     data-category="<?php echo sanitize($project['category']); ?>"
                     data-title="<?php echo sanitize($project['title']); ?>"
                     data-image="<?php echo sanitize($project['image_url']); ?>"
                     data-description="<?php echo sanitize($project['description']); ?>"
                     data-tech="<?php echo sanitize($project['tech_stack']); ?>"
                     data-demo="<?php echo sanitize($project['demo_link']); ?>"
                     data-github="<?php echo sanitize($project['github_link']); ?>">
                    
                    <div class="project-img-wrapper">
                        <img src="<?php echo sanitize($project['image_url']); ?>" alt="<?php echo sanitize($project['title']); ?>" class="project-img">
                        <span class="project-category-badge"><?php echo sanitize($project['category']); ?></span>
                    </div>

                    <h3><?php echo sanitize($project['title']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem; flex-grow: 1;">
                        <?php echo sanitize($project['summary']); ?>
                    </p>

                    <div class="tech-tags">
                        <?php 
                        $techs = explode(',', $project['tech_stack']);
                        foreach ($techs as $tech): 
                        ?>
                            <span class="tech-tag"><?php echo sanitize(trim($tech)); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="project-footer">
                        <button class="btn btn-outline view-project-btn" style="flex: 1; padding: 0.5rem 1rem; font-size: 0.85rem;">
                            <i class="bi bi-eye-fill"></i> <?php echo __t('project_view_details'); ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Project Details Modal Popup -->
<div class="modal-overlay" id="projectModal">
    <div class="glass-card modal-container" style="position: relative;">
        <button class="modal-close-btn" id="modalCloseBtn">&times;</button>
        <div class="project-img-wrapper" style="height: 280px; margin-bottom: 1.5rem;">
            <img src="" id="modalImage" alt="Project Image" class="project-img">
        </div>
        <span class="section-tag" id="modalCategory">Category</span>
        <h2 id="modalTitle" style="margin: 0.5rem 0 1rem 0;">Project Title</h2>
        <p id="modalDescription" style="line-height: 1.8; color: var(--text-main); margin-bottom: 1.5rem;">Project details...</p>

        <div style="margin-bottom: 1.5rem;">
            <h5 style="color: var(--accent-cyan); margin-bottom: 0.5rem;">Tech Stack & Frameworks</h5>
            <div id="modalTechStack" style="font-family: var(--font-code); background: var(--bg-glass); padding: 0.8rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);"></div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <a href="#" id="modalDemoLink" target="_blank" class="btn btn-primary" style="flex: 1; justify-content: center;">
                <i class="bi bi-box-arrow-up-right"></i> <?php echo __t('project_live_demo'); ?>
            </a>
            <a href="#" id="modalGithubLink" target="_blank" class="btn btn-outline" style="flex: 1; justify-content: center;">
                <i class="bi bi-github"></i> <?php echo __t('project_source_code'); ?>
            </a>
        </div>
    </div>
</div>

<!-- 5. Skills Section -->
<section id="skills" class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('nav_skills'); ?></span>
            <h2 class="section-title"><?php echo __t('skills_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('skills_subtitle'); ?></p>
        </div>

        <div class="skills-grid">
            <?php
            // Group skills by category
            $categorizedSkills = [];
            foreach ($skills as $skill) {
                $categorizedSkills[$skill['category']][] = $skill;
            }
            ?>

            <?php foreach ($categorizedSkills as $categoryName => $catSkills): ?>
                <div class="glass-card">
                    <h3 style="margin-bottom: 1.5rem; color: var(--accent-indigo); border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                        <i class="bi bi-layers-fill"></i> <?php echo sanitize($categoryName); ?>
                    </h3>

                    <?php foreach ($catSkills as $skill): ?>
                        <div class="skill-item">
                            <div class="skill-info">
                                <span><i class="<?php echo sanitize($skill['icon_class']); ?>" style="color: var(--accent-cyan);"></i> <?php echo sanitize($skill['name']); ?></span>
                                <span style="color: var(--accent-indigo);"><?php echo (int)$skill['proficiency_percent']; ?>%</span>
                            </div>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width: <?php echo (int)$skill['proficiency_percent']; ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 6. Career & Education Timeline Section -->
<section id="experience" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('nav_experience'); ?></span>
            <h2 class="section-title"><?php echo __t('experience_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('experience_subtitle'); ?></p>
        </div>

        <div class="timeline">
            <?php foreach ($experiences as $exp): ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="glass-card">
                        <span class="timeline-date">
                            <i class="bi bi-calendar-event"></i> <?php echo sanitize($exp['start_date']); ?> — <?php echo sanitize($exp['end_date']); ?>
                        </span>

                        <h3 style="margin-top: 0.4rem; color: var(--text-heading);"><?php echo sanitize($exp['role_title']); ?></h3>
                        <h4 style="color: var(--accent-cyan); font-weight: 500; font-size: 1.05rem; margin-bottom: 0.75rem;">
                            <i class="bi bi-building"></i> <?php echo sanitize($exp['organization']); ?> 
                            <?php if (!empty($exp['location'])): ?>
                                <span style="color: var(--text-muted); font-size: 0.9rem;">(<?php echo sanitize($exp['location']); ?>)</span>
                            <?php endif; ?>
                        </h4>

                        <p style="color: var(--text-main); line-height: 1.7; font-size: 0.95rem;">
                            <?php echo nl2br(sanitize($exp['description'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 7. Services Section -->
<section id="services" class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('nav_services'); ?></span>
            <h2 class="section-title"><?php echo __t('services_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('services_subtitle'); ?></p>
        </div>

        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="glass-card">
                    <div class="service-icon">
                        <i class="<?php echo sanitize($service['icon']); ?>"></i>
                    </div>

                    <h3 style="margin-bottom: 0.75rem; color: var(--text-heading);"><?php echo sanitize($service['title']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                        <?php echo sanitize($service['description']); ?>
                    </p>

                    <?php if (!empty($service['features'])): ?>
                        <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; font-size: 0.85rem; color: var(--accent-cyan);">
                            <i class="bi bi-check-circle-fill"></i> <?php echo sanitize($service['features']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 8. Testimonials Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('testimonials_title'); ?></span>
            <h2 class="section-title"><?php echo __t('testimonials_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('testimonials_subtitle'); ?></p>
        </div>

        <div class="testimonials-grid">
            <?php foreach ($testimonials as $t): ?>
                <div class="glass-card">
                    <div class="rating-stars">
                        <?php for ($i = 0; $i < (int)$t['rating']; $i++): ?>
                            <i class="bi bi-star-fill"></i>
                        <?php endfor; ?>
                    </div>

                    <p style="font-style: italic; font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.5rem; color: var(--text-main);">
                        "<?php echo sanitize($t['message']); ?>"
                    </p>

                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <?php if (!empty($t['avatar_url'])): ?>
                            <img src="<?php echo sanitize($t['avatar_url']); ?>" alt="<?php echo sanitize($t['client_name']); ?>" class="testimonial-avatar">
                        <?php endif; ?>
                        <div>
                            <h4 style="font-size: 1rem; color: var(--text-heading);"><?php echo sanitize($t['client_name']); ?></h4>
                            <span style="font-size: 0.85rem; color: var(--accent-indigo);"><?php echo sanitize($t['client_role']); ?>, <?php echo sanitize($t['company']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 9. Contact Form Section -->
<section id="contact" class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><?php echo __t('nav_contact'); ?></span>
            <h2 class="section-title"><?php echo __t('contact_title'); ?></h2>
            <p class="section-subtitle"><?php echo __t('contact_subtitle'); ?></p>
        </div>

        <div class="contact-grid">
            <div>
                <div class="glass-card" style="margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1.25rem; color: var(--accent-indigo);"><i class="bi bi-chat-left-text-fill"></i> Direct Contact Info</h3>

                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="social-icon" style="background: rgba(99, 102, 241, 0.1); border-color: var(--accent-indigo); color: var(--accent-indigo);">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Email Me</span>
                                <strong style="display: block; font-size: 0.95rem; color: var(--text-heading);"><?php echo sanitize($profile['email']); ?></strong>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="social-icon" style="background: rgba(16, 185, 129, 0.1); border-color: var(--accent-emerald); color: var(--accent-emerald);">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <div>
                                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">WhatsApp / Phone</span>
                                <strong style="display: block; font-size: 0.95rem; color: var(--text-heading);"><?php echo sanitize($profile['phone']); ?></strong>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="social-icon" style="background: rgba(6, 182, 212, 0.1); border-color: var(--accent-cyan); color: var(--accent-cyan);">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Location</span>
                                <strong style="display: block; font-size: 0.95rem; color: var(--text-heading);"><?php echo sanitize($profile['location']); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card">
                <div id="contactAlert" class="alert-message"></div>

                <form id="contactForm">
                    <div class="form-group">
                        <label class="form-label"><?php echo __t('contact_name'); ?> *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?php echo __t('contact_email'); ?> *</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?php echo __t('contact_subject'); ?></label>
                        <input type="text" name="subject" class="form-control" placeholder="Project Inquiry / Job Opportunity">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?php echo __t('contact_message'); ?> *</label>
                        <textarea name="message" class="form-control" placeholder="Write details about your project or message..." required></textarea>
                    </div>

                    <button type="submit" id="contactSubmitBtn" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.9rem;">
                        <i class="bi bi-send-fill"></i> <?php echo __t('contact_send'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
