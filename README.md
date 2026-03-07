# PHP Auction Site

A simple auction website built using PHP, MySQL, HTML, CSS, and JavaScript.

## Project Structure

- `public/` - web entrypoints, CSS, and JavaScript
- `features/` - shared feature snippets used by public pages
- `includes/` - configuration, DB connection, and helpers
- `sql/auction_db.sql` - full schema for a clean database
- `sql/add_email_verification.sql` - migration for older databases
- `storage/mail/` - local email previews when running in dev mail-log mode

## Technologies Used

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Local Dev Tools:** XAMPP, PhpStorm

## Development Workflow

This project uses Git with a `main` and `develop` branch structure:

- Code is committed to `develop` during feature development
- Once tested, changes are merged into `main`
- To practice the workflow commonly used among professionals

## Run Locally Without Installing PHP

1. Install and start Docker Desktop.
2. From the project root, run `docker compose up --build`.
3. Open `http://localhost:8080`.
4. On first startup, MariaDB imports `sql/auction_db.sql` automatically.

Verification emails are logged to `storage/mail/` in local development, so you can complete signup without SMTP or a host PHP install.

If you already created the database before these fixes, run `sql/add_email_verification.sql` once or recreate the DB volume with `docker compose down -v`.

## Sample Users

- **Email:** `user@epita.fr`
- **Password:** `tristan`
- **Email:** `minkyu.shim@epita.fr`
- **Password:** `1234`
