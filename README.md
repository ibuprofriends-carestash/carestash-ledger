# Health Records Management System — Admin Module

A PHP + MySQL (PDO) admin panel for managing patient health records, built for XAMPP.

## Features
- Admin login (session-based, bcrypt-hashed password)
- Create, Read (list + view), Update, Delete patient records
- Search patients by name

## Setup (XAMPP)

1. **Copy the project folder**
   Copy this whole `health_records` folder into your XAMPP `htdocs` directory, e.g.:
   `C:\xampp\htdocs\health_records` (Windows) or `/Applications/XAMPP/htdocs/health_records` (Mac).

2. **Start Apache and MySQL** in the XAMPP Control Panel.

3. **Import the database**
   Open `http://localhost/phpmyadmin`, click **Import**, and select
   `database/health_records_db.sql`. This creates the `health_records_db` database with
   two tables (`admins`, `patients`) and a default admin account.

4. **Check the DB config**
   `config/database.php` is already set for the default XAMPP MySQL user (`root`, no password).
   Change it there if your setup differs.

5. **Open the app**
   Visit `http://localhost/health_records/` in your browser. It will redirect you to the
   admin login page.

## Default Admin Login
- **Username:** `admin`
- **Password:** `admin123`

⚠️ Change this password (or add a new admin and delete this one) before using this with
real patient data.

## Project Structure
```
health_records/
├── config/
│   └── database.php        # PDO DB connection
├── includes/
│   └── session.php         # shared session starter
├── database/
│   └── health_records_db.sql
├── admin/
│   ├── auth_check.php      # login guard, include at top of protected pages
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php       # READ: list + search
│   ├── create.php          # CREATE
│   ├── edit.php            # UPDATE
│   ├── view.php            # READ: single record
│   ├── delete.php          # DELETE
│   └── includes/
│       ├── header.php
│       └── footer.php
└── index.php                # redirects to admin/login.php
```

## Next Steps
This covers the **admin** side. Natural next additions:
- A public/staff-facing section with more limited permissions
- Pagination on the dashboard table for large record sets
- File uploads (e.g. lab results, scanned documents) attached to a record
- An audit log of who created/edited/deleted each record
