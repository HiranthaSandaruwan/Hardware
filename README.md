Hardware Repair Request Tracker

A simple web-based application to manage hardware/software repair requests between Customers (Users), Admins, and Technicians.

Built using only: HTML, CSS, JavaScript, PHP, MySQL (no frameworks or external services) — intended as an educational project.

Overview
--------
This project implements a lightweight repair tracking system where:
- Customers submit repair requests and accept technician proposals.
- Technicians propose appointment slots, create receipts, and update repair status.
- Admins manage users and review high-level reports.

This repository is intended for educational/demo use only. Passwords in the seeded database are plain text for convenience; do not deploy this code as-is to a public environment.

Roles & common flows
--------------------
- Customer (user): register -> wait for admin approval -> create requests -> accept proposals -> view receipts & give feedback.
- Technician: register -> admin approves -> propose appointment slots -> update repair progress -> create receipts.
- Admin: approve user registrations, manage users, and view system reports.

Current schema notes (high level)
---------------------------------
The authoritative schema is provided in `sql/database.sql`. Key points:
- `users` stores basic accounts (user_id, username, password, role, status, is_disabled, created_at).
- Profile tables split role-specific data:
  - `customer_profile` (customer_id FK → users): full_name, phone, email, address.
  - `technician_profile` (technician_id FK → users): full_name, phone, email, specialization, experience_years, availability_notes.
- `requests` now uses a `state` column (several workflow states) and `assigned_to` to reference the technician; legacy per-request columns were removed.
- `repair_updates` contains status entries (no free-text technician "note" field — that column was removed).
- The legacy `comments` table was removed from the schema during cleanup and is no longer used.

Files and layout (important files)
----------------------------------
- `assets/css/style.css` — main stylesheet (site-wide UI and recently centralized scrollbar rules).
- `auth/` — login / register / logout pages.
- `admin/` — admin dashboards and user management (e.g., `users_manage.php`).
- `customer/` and `technician/` — role-specific pages (requests, completed work, proposals).
- `partials/` — shared header, footer, and navigation.
- `sql/database.sql` — canonical schema + sample seed data (import this into your local MySQL to create the DB).

Important changes since earlier drafts
-------------------------------------
- Removed the legacy `comments` table and associated UI.
- Removed the `note` column from `repair_updates` and updated server-side code accordingly.
- Unified payments/feedback UI into consistent boxed components for customer and technician views.
- Re-themed `help.php` and `features.php` to match site UI and moved scrollbar hiding into `assets/css/style.css` (site-wide behavior).
- Reworked `requests` schema to use `state` and `assigned_to` instead of older per-request columns.

Database connection snippet (`db.php`)
-------------------------------------
Edit credentials as needed for your environment.

```php
<?php
$dbhost='localhost';
$dbuser='root';
$dbpass='';
$dbname='repair_tracker';
$mysqli = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if($mysqli->connect_errno){
    die('DB Connect failed: '.$mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>
```

Setup & run (local)
-------------------
1. Install XAMPP/WAMP (Windows) or a LAMP stack (Linux).
2. Place this project folder in your web server root (e.g., `htdocs/Hardware`).
3. Import `sql/database.sql` into MySQL to create the schema and seed data.
4. Adjust `db.php` if your DB credentials differ.
5. Open `http://localhost/<your-path>/auth/login.php` in your browser.

Seeded accounts (for testing)
-----------------------------
- Admin: `admin` / `admin`
- Demo User: `uoc` / `uoc` (customer profile seeded)
- Demo Technician: `tech` / `tech` (technician profile seeded)

UI notes & limitations
----------------------
- The UI is deliberately small and dependency-free (no Bootstrap). Styling is centralized in `assets/css/style.css`.
- Scrollbars are visually hidden site-wide (CSS in `assets/css/style.css`) while keeping scrolling functional; consider restoring visible scrollbars for accessibility if desired.
- Passwords in the seed file are plain-text to simplify testing; secure hashing is strongly recommended before any public deployment.

Suggested next steps for production hardening
--------------------------------------------
- Migrate passwords to bcrypt/password_hash and update login logic.
- Add server-side validation and stronger input sanitization.
- Add pagination and server-side filtering to large admin tables (e.g., `users_manage.php`).
- Consider adding a migration script under `sql/migrations/` to document schema changes for deployments.

Credits
-------
Built as an educational project.