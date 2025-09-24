// Simple shared JS utilities for PlaceTale

function validateContactForm() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let ok = true;

        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const message = document.getElementById('message');

        if (!name.value.trim()) { name.parentElement.classList.add('error'); ok = false; } else { name.parentElement.classList.remove('error'); name.parentElement.classList.add('success'); }
        if (!validateEmail(email.value)) { email.parentElement.classList.add('error'); ok = false; } else { email.parentElement.classList.remove('error'); email.parentElement.classList.add('success'); }
        if (message.value.trim().length < 20) { message.parentElement.classList.add('error'); ok = false; } else { message.parentElement.classList.remove('error'); message.parentElement.classList.add('success'); }

        if (ok) {
            alert('Thanks! Your message has been sent.');
            form.reset();
            document.querySelectorAll('.form-group').forEach(g => g.classList.remove('success'));
        }
    });
}

// Events: fetch external JSON, sort, filter, paginate
function renderEventsFromJson(url, pageSize = 6) {
    const container = document.getElementById('eventsContainer');
    const pager = document.getElementById('pagination');
    const filterPlace = document.getElementById('filterPlace');
    const sortBy = document.getElementById('sortBy');
    const searchTerm = document.getElementById('searchTerm');
    if (!container || !pager) return;

    let all = [];
    let current = 1;

    function applySortFilter() {
        let list = [...all];
        const place = (filterPlace && filterPlace.value) ? filterPlace.value : '';
        const query = (searchTerm && searchTerm.value) ? searchTerm.value.toLowerCase() : '';
        const sort = (sortBy && sortBy.value) ? sortBy.value : 'date_asc';

        if (place) list = list.filter(ev => ev.place === place);
        if (query) list = list.filter(ev => ev.title.toLowerCase().includes(query));

        list.sort((a, b) => {
            if (sort === 'date_asc') return a.date.localeCompare(b.date);
            if (sort === 'date_desc') return b.date.localeCompare(a.date);
            if (sort === 'title_asc') return a.title.localeCompare(b.title);
            if (sort === 'title_desc') return b.title.localeCompare(a.title);
            return 0;
        });

        return list;
    }

    function render() {
        const list = applySortFilter();
        const pages = Math.max(1, Math.ceil(list.length / pageSize));
        if (current > pages) current = pages;
        const start = (current - 1) * pageSize;
        const items = list.slice(start, start + pageSize);

        container.innerHTML = items.map(ev => `
            <div class="event-card">
                <h3>${ev.title}</h3>
                <div class="event-meta"><i class="fas fa-calendar"></i> ${new Date(ev.date).toLocaleDateString()} &nbsp; • &nbsp; <i class="fas fa-map-marker-alt"></i> ${ev.place}</div>
                <p>${ev.description}</p>
            </div>
        `).join('');

        pager.innerHTML = '';
        for (let p = 1; p <= pages; p++) {
            const btn = document.createElement('button');
            btn.textContent = p;
            if (p === current) btn.classList.add('active');
            btn.addEventListener('click', () => { current = p; render(); });
            pager.appendChild(btn);
        }
    }

    fetch(url)
        .then(r => r.json())
        .then(data => {
            all = Array.isArray(data) ? data : [];
            render();
        })
        .catch(() => {
            all = [];
            render();
        });

    if (filterPlace) filterPlace.addEventListener('change', () => { current = 1; render(); });
    if (sortBy) sortBy.addEventListener('change', () => { current = 1; render(); });
    if (searchTerm) searchTerm.addEventListener('input', () => { current = 1; render(); });
}

document.addEventListener('DOMContentLoaded', () => {
    validateContactForm();
    // Toggle header items based on session
    try {
        fetch('session_info.php', { credentials: 'same-origin' })
            .then(r => r.ok ? r.json() : { loggedIn: false })
            .then(info => {
                const nav = document.querySelector('.nav-links');
                if (!nav) return;
                const loginItem = nav.querySelector('a[href="login.html"]');
                let profileItem = nav.querySelector('a[href="profile.php"]');
                let logoutItem = nav.querySelector('a[href="logout.php"]');

                if (info.loggedIn) {
                    // Add/ensure Profile and Logout links
                    if (!profileItem) {
                        const li = document.createElement('li');
                        li.innerHTML = `<a href="profile.php"><i class="fas fa-user"></i> ${info.name ? info.name.split(' ')[0] : 'Profile'}</a>`;
                        nav.appendChild(li);
                        profileItem = li.querySelector('a');
                    } else if (info.name) {
                        profileItem.innerHTML = `<i class="fas fa-user"></i> ${info.name.split(' ')[0]}`;
                    }
                    if (!logoutItem) {
                        const li = document.createElement('li');
                        li.innerHTML = `<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>`;
                        nav.appendChild(li);
                    }
                    // Hide Login link
                    if (loginItem) loginItem.parentElement.style.display = 'none';
                } else {
                    // Ensure Login is visible and remove profile/logout if present
                    if (loginItem) loginItem.parentElement.style.display = '';
                    if (profileItem) profileItem.parentElement.remove();
                    if (logoutItem) logoutItem.parentElement.remove();
                }
            })
            .catch(() => {});
    } catch (_) {}
});


