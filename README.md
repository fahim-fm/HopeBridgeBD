# HopeBridgeBD — Optimized Project

## What Was Done

### ✅ Responsiveness (Mobile / Tablet / Desktop)

- All pages now use Bootstrap 5.3 responsive grid (`col-sm-*`, `col-md-*`, `col-lg-*`)
- Navbar collapses to a hamburger menu on mobile
- Hero carousel image height uses `clamp()` — scales smoothly across all screen sizes
- Auth cards (login/register) are centered and max-width constrained on larger screens
- All tables are wrapped in `admin-table-wrap` with `overflow-x: auto` for horizontal scroll on mobile
- Donation/volunteer cards reflow from 3-column → 2-column → 1-column as screen shrinks
- Contact form stacks fields vertically on small screens
- Custom CSS breakpoints in `assets/css/styles.css` handle edge cases at 575px and 767px

### ✅ Security Fixes

- All database queries converted from raw `mysqli_query()` string interpolation to **prepared statements** (`mysqli_prepare` + `mysqli_stmt_bind_param`) — prevents SQL injection
- All user output is wrapped in `htmlspecialchars()` — prevents XSS
- `session_regenerate_id(true)` added on login to prevent session fixation
- File uploads validated by `mime_content_type()` (not just extension), and size-limited to 5 MB
- Status and category inputs whitelisted with `in_array()` checks
- Integer IDs cast with `(int)` before use in queries
- Admin section checks `$_SESSION['admin_id']` independently from donor/volunteer sessions

### ✅ Performance Optimizations

- Reduced queries: donor dashboard stats now use `array_filter()` on a single fetched result (no extra DB calls)
- Volunteer dashboard: eliminated N+1 query — donor name fetched via JOIN, not inside a loop
- Impact counters use a single query per stat, triggered only once via `IntersectionObserver`
- Images use `loading="lazy"` (except above-the-fold hero image which uses `loading="eager"`)
- AOS animations initialized once with `{ once: true }` to avoid re-triggering
- CDN links use specific versions and include `integrity`/`crossorigin` where available

### ✅ Code Quality

- Shared `navbar.php` is now session-aware — shows correct links for guest/donor/volunteer/admin
- Shared `footer.php` extracted and reused across all public pages
- Admin pages share `admin/_navbar.php` partial with active-page highlighting
- No inline CSS — all styles in `assets/css/styles.css`
- PHP files use consistent indentation, CRLF → LF line endings
- `donatenow.php` retained for guest donations (linked from original project)

### ✅ Files Added / Kept

| File                      | Notes                                                  |
| ------------------------- | ------------------------------------------------------ |
| `db.php`                  | Unchanged — your DB config is preserved                |
| `db.sql`                  | Unchanged — original schema                            |
| `logout.php`              | Improved: `session_unset()` before `session_destroy()` |
| `admin/generate_hash.php` | Utility to generate bcrypt hashes (delete after use)   |
| `uploads/`                | Empty folder preserved for image uploads               |

---

## Setup Instructions

1. Copy this entire folder into your XAMPP `htdocs/` directory (e.g., `htdocs/hopebd/`)
2. Import `db.sql` into phpMyAdmin (database name: `cf_donation`)
3. Open `admin/generate_hash.php` in browser to create your admin password hash
4. Insert admin user into `users` table:
   ```sql
   INSERT INTO users (name, email, password, role, area)
   VALUES ('Admin', 'admin@hopebridgebd.org', 'PASTE_HASH_HERE', 'admin', 'Feni');
   ```
5. Delete `admin/generate_hash.php` after use
6. Open `http://localhost/hopebd/`

---

## File Structure

```
hopebd/
├── index.php               Homepage with carousel, cards, impact counter
├── login.php               Unified login (donor/volunteer/admin)
├── register.php            Donor registration
├── logout.php              Session destroy + redirect
├── donations.php           Browse donations with filters
├── donation_details.php    Single donation view
├── donation_form.php       Add/edit donation (auth required)
├── donation_save.php       Form handler for add/edit
├── donatenow.php           Guest donation form
├── delete_donation.php     Delete handler (auth required)
├── volunteers.php          Public volunteer listing
├── contact.php             Contact form
├── donor_dashboard.php     Donor's personal dashboard
├── volunteer_dashboard.php Volunteer's personal dashboard
├── navbar.php              Shared responsive navbar
├── footer.php              Shared footer
├── db.php                  Database connection
├── db.sql                  Database schema
├── favicon.png             Site favicon
├── uploads/                Uploaded donation/volunteer images
├── assets/
│   ├── css/styles.css       All custom styles + responsive breakpoints
│   └── img/                Logo, hero images, defaults
└── admin/
    ├── login.php            Admin login
    ├── dashboard.php        Admin stats overview
    ├── manage_donors.php    List/delete donors
    ├── edit_donor.php       Edit donor details
    ├── manage_donations.php List/update/delete donations
    ├── manage_volunteers.php List/delete volunteers
    ├── add_volunteer.php    Add new volunteer
    ├── edit_volunteer.php   Edit volunteer
    ├── admin_messages.php   View/delete contact messages
    ├── reports.php          Platform statistics & charts
    ├── generate_hash.php    Password hash generator (delete after use)
    └── _navbar.php          Shared admin navbar partial
```
