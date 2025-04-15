<?php
// --- Database Configuration ---
$dbHost     = 'localhost'; // Or your MySQL server address
$dbUsername = 'webapp_user'; // The user you created
$dbPassword = 'your_password'; // The password for the user
$dbName     = 'productivity_db'; // The database name

// --- Establish Database Connection ---
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check Connection
if ($conn->connect_error) {
    // Don't show detailed errors on a public site, log them instead
    die("Connection failed. Please try again later.");
    // For debugging ONLY: die("Connection failed: " . $conn->connect_error);
}

// --- Fetch Books from Database ---
// Select top 10 books, ordered by sales_rank (or ID if rank is not used)
$sql = "SELECT id, title, author, description FROM books ORDER BY sales_rank ASC, id ASC LIMIT 10";
$result = $conn->query($sql);

// We will store book data here
$books = [];
if ($result && $result->num_rows > 0) {
    // Fetch associative array (column names as keys)
    while($row = $result->fetch_assoc()) {
        $books[] = $row; // Add each book row to the $books array
    }
} else {
    // Handle case where no books are found or there's a query error
    // You might want to log the error: error_log("SQL Error: " . $conn->error);
    $error_message = "No books found or could not retrieve data.";
}

// --- Close Database Connection ---
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Productivity Books (from DB)</title>
    <link rel="stylesheet" href="style.css"> {/* Link to your CSS file */}
</head>
<body>

    <header>
        <h1>Top Selling Productivity Books</h1>
        <p>A curated list of highly recommended books to boost your productivity.</p>
    </header>

    <main>
        <div id="book-list-container">

            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        {/* Use htmlspecialchars to prevent XSS attacks */}
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="author">By <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="description"><?php echo htmlspecialchars($book['description']); ?></p>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="loading">
                    <?php echo isset($error_message) ? $error_message : 'Loading books... (or none found)'; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <footer>
        <p>Data dynamically retrieved from MySQL database.</p>
    </footer>

    {/* No need for script.js to load data anymore, but you could keep it for other interactions */}
    {/* <script src="script.js"></script> */}
</body>
</html>