# Top Productivity Books - Dynamic Web Application

## Overview

This project is a simple web application designed to display a list of top-selling productivity books. It dynamically retrieves book information (title, author, description) from a MySQL database using PHP and presents it on a styled HTML page.

This serves as a basic example of a 2-tier web application architecture:

1.  **Client Tier:** The user's web browser, which renders the HTML and CSS.
2.  **Server/Data Tier:** An Apache web server running PHP scripts that connect to a MySQL database to fetch and serve the data.

*(Previously, this project existed as a purely static version using hardcoded JavaScript data. This version represents the evolution to a dynamic, database-driven application.)*

<!-- Optional: Add a screenshot of the application here -->
<!-- ![Screenshot of the application](link/to/your/screenshot.png) -->

## Features

*   Displays a list of productivity books.
*   Retrieves book data dynamically from a MySQL database.
*   Uses PHP for server-side processing and database interaction.
*   Styled using basic CSS for a clean presentation.
*   Ordered list based on a `sales_rank` column (or fallback to `id`).
*   Basic security considerations (using `htmlspecialchars` to prevent XSS).

## Technology Stack

*   **Backend:** PHP (tested with PHP 7.x/8.x)
*   **Database:** MySQL (or compatible, e.g., MariaDB)
*   **Web Server:** Apache (with `mod_php` enabled) or another server capable of running PHP (like Nginx with PHP-FPM).
*   **Frontend:** HTML5, CSS3
*   **PHP Extension:** `mysqli` (for database connection)

## Prerequisites

Before you begin, ensure you have the following installed and configured on your system:

1.  **Apache Web Server:** ([Installation Guide](https://httpd.apache.org/docs/2.4/install.html) - varies by OS)
2.  **PHP:** ([Installation Guide](https://www.php.net/manual/en/install.php) - varies by OS)
    *   Ensure the `mysqli` extension is enabled. You can typically check this using `php -m | grep mysqli` or by looking at your `php.ini` file. Install it if necessary (e.g., `sudo apt install php-mysql` on Debian/Ubuntu).
3.  **MySQL Server:** ([Installation Guide](https://dev.mysql.com/doc/refman/8.0/en/installing.html) - varies by OS)
4.  **Git:** (Optional, for cloning the repository)

## Setup and Installation

1.  **Clone the Repository (Optional):**
    ```bash
    git clone https://github.com/your-username/your-repo-name.git
    cd your-repo-name
    ```
    *Replace `your-username/your-repo-name` with your actual GitHub repository path.*
    *Alternatively, download the project files (`index.php`, `style.css`) manually.*

2.  **Move Project Files:**
    Place the project files (`index.php`, `style.css`) into your Apache web server's document root. Common locations include:
    *   Linux (Debian/Ubuntu): `/var/www/html/`
    *   Linux (CentOS/Fedora): `/var/www/html/`
    *   macOS (built-in Apache): `/Library/WebServer/Documents/`
    *   XAMPP: `C:\xampp\htdocs\` or `/Applications/XAMPP/htdocs/`
    *   MAMP: `/Applications/MAMP/htdocs/`

    *You might want to place them in a subdirectory, e.g., `/var/www/html/books/`. If so, you'll access the app via `http://localhost/books/`.*

3.  **Database Setup:**
    *   Connect to your MySQL server using a client (e.g., `mysql` command-line tool, phpMyAdmin, MySQL Workbench).
        ```bash
        mysql -u root -p
        ```
        *(Use the appropriate username for your MySQL setup)*
    *   Create the database:
        ```sql
        CREATE DATABASE productivity_db;
        ```
    *   Switch to the newly created database:
        ```sql
        USE productivity_db;
        ```
    *   Create the `books` table:
        ```sql
        CREATE TABLE books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(150) NOT NULL,
            description TEXT,
            sales_rank INT DEFAULT 0 -- Used for ordering top sellers
        );
        ```
    *   Insert some sample data:
        ```sql
        INSERT INTO books (title, author, description, sales_rank) VALUES
        ('Atomic Habits', 'James Clear', 'An easy & proven way to build good habits & break bad ones.', 1),
        ('Getting Things Done', 'David Allen', 'The art of stress-free productivity.', 2),
        ('Deep Work', 'Cal Newport', 'Rules for focused success in a distracted world.', 3),
        ('The 7 Habits of Highly Effective People', 'Stephen Covey', 'Powerful lessons in personal change.', 4),
        ('Essentialism', 'Greg McKeown', 'The disciplined pursuit of less.', 5),
        ('The Power of Habit', 'Charles Duhigg', 'Why we do what we do in life and business.', 6),
        ('Hyperfocus', 'Chris Bailey', 'How to be more productive in a world of distraction.', 7);
        ```
    *   **(Recommended Security Practice)** Create a dedicated database user with limited privileges for the web application:
        ```sql
        -- Replace 'your_secure_password' with a strong password
        CREATE USER 'webapp_user'@'localhost' IDENTIFIED BY 'your_secure_password';

        -- Grant only SELECT permission on the specific table to this user
        GRANT SELECT ON productivity_db.books TO 'webapp_user'@'localhost';

        -- Apply the changes
        FLUSH PRIVILEGES;

        -- Exit MySQL
        EXIT;
        ```

4.  **Configure Database Credentials:**
    *   Open the `index.php` file in a text editor.
    *   Locate the Database Configuration section near the top:
        ```php
        // --- Database Configuration ---
        $dbHost     = 'localhost'; // Or your MySQL server address
        $dbUsername = 'webapp_user'; // The user you created
        $dbPassword = 'your_secure_password'; // The password for the user
        $dbName     = 'productivity_db'; // The database name
        ```
    *   **Crucially, update `$dbUsername` and `$dbPassword`** to match the user and password you created in the previous step.
    *   **Security Warning:** Avoid committing files with hardcoded passwords directly into public repositories. For production, use environment variables or configuration files stored outside the web root.

## Running the Application

1.  Ensure your Apache and MySQL services are running.
2.  Open your web browser.
3.  Navigate to the URL where you placed the files.
    *   If placed directly in the root: `http://localhost/` or `http://your-server-ip/`
    *   If placed in a subdirectory (e.g., `books`): `http://localhost/books/` or `http://your-server-ip/books/`

You should see the webpage displaying the list of productivity books fetched from your database.

## File Structure

## Security Considerations

*   **Database Credentials:** As mentioned, **never** hardcode sensitive credentials directly in code pushed to public repositories. Use environment variables or secure configuration files loaded by your PHP script.
*   **SQL Injection:** This example uses a fixed `SELECT` query without user input, making it less vulnerable to SQL injection *in its current state*. If you were to add features involving user input (like search), you **must** use **Prepared Statements** (with `mysqli` or PDO) to prevent SQL injection attacks.
*   **Cross-Site Scripting (XSS):** The script uses `htmlspecialchars()` when echoing data retrieved from the database (`$book['title']`, `$book['author']`, etc.). This is essential to prevent XSS attacks if the data in the database contains malicious HTML or JavaScript code.
*   **Error Reporting:** The `die("Connection failed...")` message in `index.php` is basic. In a production environment, you should:
    *   Disable detailed error display to the browser (`display_errors = Off` in `php.ini`).
    *   Enable error logging (`log_errors = On`) to record issues privately.
    *   Provide generic, user-friendly error messages to the browser.

## Potential Improvements / Future Work

*   **Pagination:** If the list of books grows large, implement pagination.
*   **Search/Filtering:** Add functionality to search for books by title or author.
*   **Admin Interface:** Create a separate section (with authentication) to add, edit, or delete books from the database.
*   **Framework:** Rebuild using a PHP framework like Laravel or Symfony for better structure, security features, and tooling.
*   **ORM:** Use an Object-Relational Mapper (like Eloquent or Doctrine) for more abstract and often safer database interactions.
*   **AJAX:** Use JavaScript (Fetch API or libraries like Axios) to load or update book data dynamically without full page reloads.
*   **Templating Engine:** Use a templating engine like Twig or Blade for cleaner separation of PHP logic and HTML presentation.
*   **Dockerization:** Create `Dockerfile` and `docker-compose.yml` for easier setup and deployment using containers.
*   **Unit/Integration Tests:** Add tests to ensure the application works correctly.

## License

MIT Licence
