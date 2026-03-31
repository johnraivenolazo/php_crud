# PHP CRUD Activity using PDO

Simple PHP CRUD project using pure PHP, PDO, MySQL, and Bootstrap (no login/authentication).

## Features

- Full CRUD operations for Applicants Information.
- Uses PDO prepared statements.
- Required field validation on create and update.
- Upload and update profile images (JPG, PNG, GIF, WEBP up to 2MB).
- Bootstrap-based plain UI.
- Search and filter in list page (keyword, position, minimum experience).
- Ready-to-import MySQL sample data in `seed.sql`.

## Fields (6 + image)

- Full Name
- Email
- Phone
- Address
- Position Applied
- Years of Experience
- Profile Image

## Database Setup

1. Create a MySQL database, for example `php_crud`.
2. Update your DB settings through environment variables if needed:
   - `DB_HOST` default: `127.0.0.1`
   - `DB_PORT` default: `3306`
   - `DB_NAME` default: `php_crud`
   - `DB_USER` default: `root`
   - `DB_PASS` default: empty string
3. The app auto-creates the `applicants` table when it runs.
4. To preload sample records, import `seed.sql` into MySQL using phpMyAdmin, MySQL Workbench, or the `mysql` CLI.

## Run Locally

1. Put this project in your local server folder (for example XAMPP `htdocs`).
2. Start Apache and MySQL.
3. Open the project in your browser, for example:
   - `http://localhost/php_crud/index.php`

## Notes

- Uploaded images are saved in `uploads/`.
- This project is plain style and does not include login/auth modules.
- The app now targets MySQL only and no longer includes the old PostgreSQL/Heroku setup.
