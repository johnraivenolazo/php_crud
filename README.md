# PHP CRUD Activity using PDO

Simple PHP CRUD project using PDO and Bootstrap (no login/authentication).

## Features

- Full CRUD operations for Applicants Information.
- Uses PDO prepared statements.
- Required field validation on create and update.
- Upload and update profile images (JPG, PNG, GIF, WEBP up to 2MB).
- Bootstrap-based plain UI.
- Search and filter in list page (keyword, position, minimum experience).
- Ready-to-import PostgreSQL sample data in `seed.sql`.

## Fields (6 + image)

- Full Name
- Email
- Phone
- Address
- Position Applied
- Years of Experience
- Profile Image

## Database Setup

1. For Heroku, attach Heroku Postgres to your app.
2. The app auto-reads `DATABASE_URL` and creates the table if missing.
3. Optional local run (PostgreSQL): set `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASS`.

## Run Locally

1. Put this project in your local server folder (for example XAMPP `htdocs`).
2. Start Apache and MySQL.
3. Open the project in your browser, for example:
   - `http://localhost/php_crud/index.php`

## Deploy on Heroku

1. Install Heroku CLI and login:
   - `heroku login`
2. Initialize git if needed and commit files:
   - `git init`
   - `git add .`
   - `git commit -m "Prepare Heroku deployment"`
3. Create Heroku app:
   - `heroku create your-app-name`
4. Add PHP buildpack (usually auto-detected):
   - `heroku buildpacks:set heroku/php`
5. Add Heroku Postgres (sets `DATABASE_URL` automatically):
   - `heroku addons:create heroku-postgresql:essential-0`
6. Deploy:
   - `git push heroku main`
   - If your branch is master: `git push heroku master`
7. Open app:
   - `heroku open`

## Notes

- Uploaded images are saved in `uploads/`.
- This project is plain style and does not include login/auth modules.
- To preload sample records, run `seed.sql` in PostgreSQL (Heroku `psql` or local psql).
- Heroku dyno filesystem is ephemeral, so local image uploads are temporary unless you use external storage (for example Cloudinary, S3, or similar).
