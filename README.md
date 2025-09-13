## Hardware Repair Request Tracker

A lightweight PHP + MySQL web app for managing customer hardware repair requests with role-based portals for Admin, Technicians, and Customers. It’s simple to deploy on XAMPP/LAMP and ships with demo data.

### Key Features
- Role-based access: Admin, Technician, Customer
- Customer
	- Submit new repair requests and track status
	- View proposals, appointments, receipts, and payments
	- Profile page to update registration details
- Technician
	- See approved/assigned requests, send appointment proposals
	- Accept appointments, post repair updates, issue receipts
	- Mark jobs Completed/Returned/Cannot Fix
	- Profile page to update contact details and availability
- Admin
	- Approve/reject technicians, manage users, view reports
- Feedback system (customer ↔ technician)
- Seed/demo data for instant preview

### Tech Stack
- PHP 8+ (plain PHP, no framework)
- MySQL/MariaDB
- HTML/CSS/JS (custom; responsive dark theme)

### Requirements
- PHP 8+ and MySQL 5.7+/MariaDB 10.4+
- XAMPP or LAMP/WAMP stack
- A web server configured to serve the `/Hardware` folder

### Getting Started
1) Clone or copy this repo into your web root (e.g., `c:/xampp/htdocs/Hardware`).

2) Create the database and tables, and load seed data:
	 - Open phpMyAdmin (http://localhost/phpmyadmin) or use the MySQL client.
	 - Run the SQL in `sql/database.sql`.

3) Configure DB connection in `db.php` if needed:
	 - Host, user, pass, and DB name (defaults: `localhost`, `root`, `2323`, `repair_tracker`).

4) Launch the app:
	 - Visit http://localhost/Hardware

### Default Accounts
- Admin: `admin / admin`
- Demo Customer: `uoc / uoc`
- Demo Technician: `tech / tech`

If you enabled extra demo seeds in `database.sql`, you’ll also have:
- Customers: `custA .. custE` (password `pass123`)
- Technicians: `techA .. techE` (password `pass123`)

### Project Structure
```
Hardware/
├─ auth/                # login, register (customer/technician), logout
├─ customer/            # customer dashboard, requests, proposals, profile
├─ technician/          # technician dashboard, approved/completed, proposals, profile
├─ admin/               # admin dashboard, manage users, reports
├─ partials/            # header, sidebar, footer
├─ assets/
│  ├─ css/style.css     # theme (Netflix-style dark)
│  └─ js/app.js         # small helpers and validation
├─ sql/database.sql     # schema + seeds
├─ config.php           # session + auth helpers
├─ db.php               # mysqli connection
└─ index.php            # landing
```

### Customization
- Theme: tweak colors, spacing, and components in `assets/css/style.css`.
- Base URL: change `$BASE_URL` in `config.php` if not serving under `/Hardware`.
- Password hashing: new registrations use `password_hash`; seeds include a few plain-text passwords for easy login and are supported by the login flow.

### Security Notes
- For production, replace any plain-text seeded passwords with hashed values and create real users.
- Keep `config.php` and `db.php` outside public repos if they contain secrets.

### Troubleshooting
- Blank page or errors: check PHP error log and verify DB credentials in `db.php`.
- 404s: confirm the project path matches `$BASE_URL` in `config.php`.
- No styles: ensure `assets/css/style.css` is accessible at `/Hardware/assets/css/style.css`.

### License
This project is provided as-is under the MIT License. See `LICENSE` (add if you plan to publish).

### Acknowledgements
- Built for quick deployment using classic PHP + MySQL
- UI inspired by Netflix’s dark aesthetic
