# PHP Auction Site

A simple auction website built using PHP, MySQL, HTML, CSS, and JavaScript.

## Features & Project Structure

- `/index.php` – Homepage showing all auction items
- `/login.php` – User login
- `/signup.php` – User sign-up
- `/logout.php` – Logout logic
- `/upload_item.php` – Form to upload a new item
- `/bid.php` – View item and place a bid
- `/my_items.php` – View and delete your own items

- `/features/delete_item.php` – Delete user's item logic
- `/features/bidding_history.php` – View bid history for an item
- `/features/winning_status.php` – Show the current user is winning the bid or not
- `/features/login_auth.php` – Separate file for login processing

- `/includes/db.php` – Database connection
- `/includes/config.php` – App configuration settings

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

## How to Run Locally

1. Clone this repo or download the ZIP.
2. Move the folder to your XAMPP `htdocs` directory.
3. Start Apache and MySQL from XAMPP Control Panel.
4. Import the `auction_db` SQL schema (if provided) into phpMyAdmin.
5. Open your browser and go to: http://localhost/your-folder-name
6. You can create your own account to check out the website
7. Also, you can test with these sample users:
- **Email:** user@epita.fr, minkyu.shim@epita.fr
- **Password:** tristan, 1234
