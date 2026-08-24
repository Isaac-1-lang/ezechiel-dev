/**
 * Main Interactive JavaScript for Developer Portfolio
 */

document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    initTimezoneClocks();
    initLeafletMap();
    initProjectFilters();
    initAboutTabs();
    initProjectModals();
});

/* 1. Dark / Light Theme Switcher */
function initThemeToggle() {
    const themeBtn = document.getElementById('themeToggleBtn');
    if (!themeBtn) return;

    const savedTheme = localStorage.getItem('portfolio_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    themeBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('portfolio_theme', newTheme);
        updateThemeIcon(newTheme);
    });
}

function updateThemeIcon(theme) {
    const icon = document.querySelector('#themeToggleBtn i');
    if (!icon) return;
    icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
}

/* 2. World Timezone Clocks (Developer vs Visitor) */
function initTimezoneClocks() {
    const devClockEl = document.getElementById('devLocalTime');
    const visitorClockEl = document.getElementById('visitorLocalTime');

    const updateTimes = () => {
        const now = new Date();

        // Visitor Time
        if (visitorClockEl) {
            visitorClockEl.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }

        // Developer Time (Africa/Kigali -> UTC+2)
        if (devClockEl) {
            try {
                const devTimeString = now.toLocaleTimeString('en-US', {
                    timeZone: 'Africa/Kigali',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
                devClockEl.textContent = devTimeString;
            } catch (e) {
                devClockEl.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }
    };

    updateTimes();
    setInterval(updateTimes, 1000);
}

/* 3. Leaflet Interactive Location Map */
function initLeafletMap() {
    const mapContainer = document.getElementById('map');
    if (!mapContainer || typeof L === 'undefined') return;

    const lat = parseFloat(mapContainer.dataset.lat) || -1.9441;
    const lng = parseFloat(mapContainer.dataset.lng) || 30.0619;
    const locationName = mapContainer.dataset.location || "Kigali, Rwanda";

    const map = L.map('map', {
        center: [lat, lng],
        zoom: 12,
        scrollWheelZoom: false
    });

    // Dark styled tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
        maxZoom: 19
    }).addTo(map);

    // Custom Marker
    const marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(`<b>📍 ${locationName}</b><br>Available for International Remote Roles`).openPopup();
}

/* 4. Projects Category Filter */
function initProjectFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;

            projectCards.forEach(card => {
                const category = card.dataset.category;
                if (filter === 'all' || category === filter) {
                    card.style.display = 'flex';
                    card.style.animation = 'fadeIn 0.4s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

/* 5. About Me Tab Switcher */
function initAboutTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            const target = document.getElementById(btn.dataset.tab);
            if (target) {
                target.classList.add('active');
            }
        });
    });
}

/* 6. Project Modal Popup */
function initProjectModals() {
    const modal = document.getElementById('projectModal');
    const modalClose = document.getElementById('modalCloseBtn');
    if (!modal) return;

    document.querySelectorAll('.view-project-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const card = btn.closest('.project-card');
            
            document.getElementById('modalTitle').textContent = card.dataset.title;
            document.getElementById('modalCategory').textContent = card.dataset.category;
            document.getElementById('modalImage').src = card.dataset.image;
            document.getElementById('modalDescription').textContent = card.dataset.description;
            document.getElementById('modalTechStack').textContent = card.dataset.tech;
            
            const demoBtn = document.getElementById('modalDemoLink');
            const githubBtn = document.getElementById('modalGithubLink');
            
            demoBtn.href = card.dataset.demo || '#';
            githubBtn.href = card.dataset.github || '#';

            modal.classList.add('active');
        });
    });

    if (modalClose) {
        modalClose.addEventListener('click', () => {
            modal.classList.remove('active');
        });
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
}
