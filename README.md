Hardware Repair Request Tracker

A simple web-based application to manage hardware/software repair requests between Users, Admins, and Technicians.

Built using ONLY: HTML, CSS, JavaScript, PHP, MySQL (no frameworks, no libraries, no external APIs) — per university project rules

.
🎯 Project Overview

The system allows:

    Users (customers) to register, log in, and submit repair requests.

    Admins to approve registrations and requests, and oversee the system.

    Technicians to view approved repair jobs, assign appointment times, and update repair progress.

This project is educational only — passwords are stored in plain text, minimal validation is applied. Do not deploy publicly.
👥 Roles & Workflows
User

    Register with username + password (choosing role: User or Technician).

    Wait until Admin approves registration.

    Login after approval.

    Submit a repair request (hardware/software issues).

    View pending/approved requests in dashboard.

    Accept/reject technician’s proposed appointment times.

    Track repair progress in dashboard.

Admin

    Login with default account: admin / admin.

    Approve/reject new user registrations.

    View all repair requests.

    Approve/reject user requests before technicians see them.

    Manage users (create/delete).

    View simple reports.

Technician

    Login after registration is approved by Admin.

    View assigned repair requests.

    Propose available appointment times to Users.

    Update repair progress/status in dashboard.

🗄 Database Schema (Updated)

users

    user_id INT PK AUTO_INCREMENT

    username VARCHAR(50) UNIQUE

    password VARCHAR(255) (plain text per rules)

    role ENUM('admin','user','technician')

    status ENUM('pending','approved') DEFAULT 'pending'

    created_at TIMESTAMP

requests

    request_id INT PK AUTO_INCREMENT

    user_id FK → users

    device_type, model, serial_no

    category ENUM('Hardware','Software','Other')

    description TEXT

    status ENUM('Pending','Approved','In Progress','Completed','Rejected')

    technician_id FK → users (nullable)

    appointment_time DATETIME NULL

    created_at, updated_at TIMESTAMP

comments (optional, for discussion logs)

    comment_id INT PK

    request_id FK → requests

    user_id FK → users

    comment_text TEXT

    created_at TIMESTAMP

📂 Folder Structure

/assets
  /css/style.css        # all UI styling
  /js/app.js            # form validation + UI interactivity
/auth
  register.php          # registration form
  login.php             # login page
  logout.php
/admin
  index.php             # admin dashboard
  users.php             # manage users (approve/reject)
  requests.php          # approve/reject requests
  reports.php           # simple tables
/technician
  index.php             # technician dashboard
  requests.php          # assigned requests + status updates
/user
  dashboard.php         # user dashboard
  request_new.php       # new request form
  my_requests.php       # track progress
/partials
  header.php, footer.php, nav.php
config.php              # base paths + DB setup
db.php                  # database connection
database.sql            # schema + seed data
index.php               # landing (login/register links)
features.php
help.php

🔌 Database Connection (db.php)

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

▶️ Setup & Run

    Install XAMPP/WAMP (Windows) or LAMP (Linux).

    Place folder in htdocs/ (XAMPP) or www/.

    Create DB repair_tracker and import database.sql.

    Update db.php credentials if needed.

    Visit: http://localhost/repair_tracker/auth/login.php.

Seed Accounts:

    Admin: admin / admin

    User: uoc / uoc (default, per spec

    )

🎨 UI Notes

    Clean simple forms (no Bootstrap, no JS libs).

    Sidebar navigation (PHP partial).

    Light UI with CSS-only styling.

    Minimal JavaScript (form validation + interactivity).

🚧 Limitations

    Plain-text passwords (rule requirement).

    No advanced validation (only required fields).

    Registration approval required by Admin.

    Reports basic (tables only).

👥 Credits

Built by Group X – UCSC IS1207 (2025)
University of Colombo School of Computing
Fully hand-coded with pure PHP, MySQL, JS, HTML, CSS.