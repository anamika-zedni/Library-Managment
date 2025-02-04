<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Include the database connection file
include('dbconnect.php');

// Initialize a message variable
$message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Validate Title and Author (should not be empty)
    if (empty($title) || empty($author)) {
        $message = "Title and Author fields cannot be empty.";
    }
    // Validate Price (must not be negative)
    elseif ($price < 0) {
        $message = "Price cannot be negative.";
    } else {
        // Prepare and execute the SQL query using prepared statements
        $sql = "INSERT INTO Books (title, author, price, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $title, $author, $price, $status);

        if ($stmt->execute()) {
            $message = "Book added successfully!";
        } else {
            $message = "Error adding book: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
    }

    $conn->close();
}
?>



<!-- AddBook.html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="Dashboard.php">Library Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="Dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="AddBook.php">Add Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="UpdateBook.php">Update Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="DeleteBook.php">Delete Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add Book Page -->
    <div class="container mt-5">
        <h2>Add Book</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="AddBook.php" method="post" id="addBookForm">
            <div class="mb-3">
                <label for="title" class="form-label">Book Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter book title" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" name="author" id="author" placeholder="Enter author's name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="Enter book price" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" id="status">
                    <option value="Available">Available</option>
                    <option value="Issued">Issued</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Add Book</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5 py-3 bg-dark text-white">
        All rights reserved by CSE, RUET @2024
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
