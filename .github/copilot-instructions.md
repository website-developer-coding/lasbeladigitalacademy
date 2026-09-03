# Digital Skills Academy Website — Copilot Instructions

## Project Overview

This project is the **Digital Skills Academy Website**, built with a simple, beginner-friendly Core PHP architecture for an XAMPP environment.

## Technology Stack

- HTML5
- CSS3
- Bootstrap 5
- Bootstrap Icons
- jQuery
- Core PHP only
- MySQL
- PDO
- XAMPP
- VS Code

## Mandatory Architecture Rules

1. Use Core PHP only.
2. Do not use MVC architecture.
3. Do not create `Models`, `Controllers`, or `Views` folders.
4. Do not use Laravel.
5. Do not use CodeIgniter.
6. Do not use Symfony.
7. Do not use React, Vue, or Angular.
8. Use reusable includes for the header, navbar, footer, and common functions.
9. Keep the code beginner-friendly and properly commented.
10. Do not unnecessarily rewrite working files.
11. Before modifying an existing file, inspect its current contents and preserve existing working functionality.
12. Keep filenames, database table names, and column names consistent throughout the project.
13. When a task is large, implement it in small steps and test each step before continuing.

## Database and Security Rules

1. Use PDO prepared statements for all database queries.
2. Never concatenate user input directly into SQL.
3. Use `password_hash()` when creating passwords.
4. Use `password_verify()` during login.
5. Use PHP sessions for admin authentication.
6. Use CSRF protection on all state-changing forms.
7. Escape database and user output with `htmlspecialchars()`.
8. Validate all `GET` and `POST` input server-side.
9. Validate uploaded files securely, including type, size, and safe storage handling.
10. DELETE operations must use POST, never GET.
11. Do not expose database credentials or detailed database errors to users.
12. Do not invent database columns that are not present in `academy.sql`.
13. If a required database field is missing, explain the issue before changing the schema.

## Academy Courses

The supported courses are:

- Website Development
- Digital Marketing
- Graphic Designing
- Artificial Intelligence
- Machine Learning
- Cyber Security
- Python Programming
- UI/UX Design
- Freelancing
- E-Commerce
- Video Editing
- Database Management

Keep course names consistent wherever they appear in the UI, PHP code, and database records.

## Public Pages

The public website includes:

- `index.php`
- `about.php`
- `services.php`
- `gallery.php`
- `enrollment.php`
- `courses.php`
- `course-details.php`
- `fees.php`
- `syllabus.php`
- `contact.php`

## Admin Modules

The admin area includes:

- Authentication
- Dashboard
- Courses CRUD
- Services CRUD
- Fees CRUD
- Syllabus CRUD
- Gallery CRUD
- Enrollment Management
- Contact Messages

All admin state-changing actions must enforce authentication, authorization, CSRF protection, server-side validation, and appropriate HTTP methods.

## Implementation Workflow

1. Inspect the relevant existing files before making changes.
2. Check `academy.sql` before using or adding database fields.
3. Reuse existing includes, naming conventions, and working functionality.
4. Make the smallest focused change that satisfies the task.
5. Validate all input on the server and escape all rendered output.
6. Test the changed page or module in the XAMPP environment before continuing.
7. For larger tasks, work in small increments and verify each increment.
8. Clearly explain any required schema change before applying it.
