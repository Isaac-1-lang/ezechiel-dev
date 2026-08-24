    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="social-links">
                <?php if (!empty($profile['github'])): ?>
                    <a href="<?php echo sanitize($profile['github']); ?>" target="_blank" class="social-icon" title="GitHub"><i class="bi bi-github"></i></a>
                <?php endif; ?>
                <?php if (!empty($profile['linkedin'])): ?>
                    <a href="<?php echo sanitize($profile['linkedin']); ?>" target="_blank" class="social-icon" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <?php endif; ?>
                <?php if (!empty($profile['twitter'])): ?>
                    <a href="<?php echo sanitize($profile['twitter']); ?>" target="_blank" class="social-icon" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                <?php endif; ?>
                <?php if (!empty($profile['whatsapp'])): ?>
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $profile['whatsapp']); ?>" target="_blank" class="social-icon" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                <?php endif; ?>
                <?php if (!empty($profile['email'])): ?>
                    <a href="mailto:<?php echo sanitize($profile['email']); ?>" class="social-icon" title="Email"><i class="bi bi-envelope-fill"></i></a>
                <?php endif; ?>
            </div>

            <p>&copy; <?php echo date('Y'); ?> <strong><?php echo sanitize($profile['full_name']); ?></strong>. <?php echo __t('footer_copyright'); ?></p>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.4rem;"><?php echo __t('footer_tagline'); ?></p>
        </div>
    </footer>

    <!-- Leaflet JS for Maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Custom Scripts -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/contact.js"></script>
</body>
</html>
