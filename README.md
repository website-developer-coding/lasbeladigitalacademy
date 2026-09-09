# Lasbela Digital Skills Academy

Lasbela Digital Skills Academy is a web-based digital skills training platform designed to provide practical and professional technology education.

## Project Overview

The website provides information about digital skills courses, services, course bundles, student enrollment, fees, gallery, and contact information.

It also includes an admin panel for managing website content using CRUD functionality.

## Technologies Used

- HTML5
- CSS3
- Bootstrap
- JavaScript
- jQuery
- Core PHP
- MySQL
- XAMPP

## Main Features

- Home Page
- About Us
- Services
- Courses
- Course Details
- Course Enrollment
- Course Bundles
- Fees
- Gallery
- Contact Us
- Student Registration
- Admin Login
- Admin Dashboard
- CRUD Operations
- Image Upload
- MySQL Database

## Admin Panel

The admin panel allows administrators to manage website data such as:

- Services
- Courses
- Bundles
- Gallery
- Students
- Contact Messages

## Database

The project uses MySQL as the database system.

The SQL database file is available in the `database` folder.

## Local Development

This project can be run locally using XAMPP.

1. Install XAMPP.
2. Start Apache and MySQL.
3. Copy the project into:

   `C:\xampp\htdocs\`

4. Import the SQL file into phpMyAdmin.
5. Update the private `.env` file with your MySQL settings if they differ from XAMPP defaults.
6. Open the project in your browser.

After importing the SQL file, use the administrator account seeded by the SQL file. The current seed email is `lasbeladigitalacademy@gmail.com`. Change its password immediately after the first login. The password is stored as a `password_hash()` value; never store a plain-text password.

## Railway Deployment

This repository includes a root `Dockerfile` and `Caddyfile` for Railway. Railway should deploy the root Dockerfile automatically when the repository is connected.

1. Push the repository to GitHub. Do not commit `.env`; it is excluded by `.gitignore` and `.dockerignore`.
2. Create a Railway project and add a MySQL service.
3. Add a web service from the GitHub repository and deploy it using the root `Dockerfile`.
4. Link the MySQL service variables to the web service. The application accepts Railway's native names (`MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`) and also supports these explicit names: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD`.
5. Import `database/lasbeladigitalacademy.sql` into the Railway MySQL database **once** before opening the website. This file drops and recreates the application tables, so never run it against a database containing important data.
6. In Railway, configure a Volume mounted at `/app/uploads` if admin-uploaded images must survive restarts and redeployments. The database stores image filenames, while Railway's default container filesystem is temporary.
7. Verify the homepage, course pages, enrollment form, contact form, and admin login after deployment. Railway supplies the `PORT` variable automatically.

The SQL seed email is `lasbeladigitalacademy@gmail.com`; verify or reset the seed password before launch, then remove demo records and credentials from the public deployment.

## Developer

**website-developer-coding**

## Project Status

Completed as a web development project for Lasbela Digital Skills Academy.
