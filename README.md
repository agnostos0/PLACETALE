# PlaceTale – Practical 6

This project implements registration and authentication using PHP sessions, a signed Remember Me cookie, and a session-aware header across pages. It also renders events from `events.json` with sorting, filtering, and pagination.

## Sessions and Cookies
- `auth.php`: starts secure sessions; defines `is_logged_in()`; auto-logins using signed `pt_remember` cookie if valid and not expired.
- `login_process.php`: verifies user from `registrations.jsonl`, sets `$_SESSION['user_email']`/`$_SESSION['user_name']`. If “Remember me” checked, sets `pt_remember` (HMAC-signed, 7 days). Redirects to `dashboard.php`.
- `logout.php`: destroys session, clears `pt_remember`, redirects back to `login.html`.
- `dashboard.php`, `profile.php`: protected with `auth.php`; redirect to `login.html` if not logged in.
- `session_info.php`: returns JSON `{ loggedIn, name, email }` for header.
- `scripts.js`: on each page load, fetches `session_info.php` and toggles header links (Login ↔ Profile/Logout). Ensure pages include `<script src="scripts.js"></script>`.

## Registration
- `register.php`: sanitizes inputs, validates email/password, hashes password with `password_hash`, stores entries in `registrations.csv` and `registrations.jsonl`, then redirects to `success.html` or `error.html`.

## Events (External JSON)
- `events.json`: data source.
- `events.html`: filter (place), sort (date/title), search (title) UI.
- `scripts.js`: `renderEventsFromJson('events.json')` fetches and paginates with client-side sort/filter/search.

## Run (XAMPP)
1. Start Apache.
2. Open `http://localhost/PlaceTale/`.
3. Sign up (`signup.html`) → Login (`login.html`).
4. After login, header shows Profile/Logout across pages; protected pages require auth.

## Notes
- Cookie signing secret is hardcoded for demo; use env vars and HTTPS in production.
- File storage is for teaching. For real apps, migrate to MySQL (schema previously provided).
- `.gitignore` excludes user data files (`registrations.csv`, `registrations.jsonl`).
