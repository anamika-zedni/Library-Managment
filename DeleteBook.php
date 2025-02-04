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

// Initialize variables
$message = "";
$bookId = "";

// Check if a Book ID is passed in the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $bookId = $_GET['id'];
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the Book ID from the form
    $bookId = $_POST['deleteBookId'];

    // Validate the Book ID
    if (empty($bookId)) {
        $message = "Please enter a Book ID.";
    } else {
        // Prepare the SQL query to delete the book
        $sql = "DELETE FROM Books WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $message = "Book with ID $bookId deleted successfully!";
            } else {
                $message = "Book with ID $bookId does not exist.";
            }
        } else {
            $message = "Error deleting book: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Book</title>
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
                    <li class="nav-item"><a class="nav-link" href="AddBook.php">Add Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="UpdateBook.php">Update Book</a></li>
                    <li class="nav-item"><a class="nav-link active" href="DeleteBook.php">Delete Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Delete Book Page -->
    <div class="container mt-5">
        <h2>Delete Book</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="DeleteBook.php" method="post" id="deleteBookForm">
            <div class="mb-3">
                <label for="deleteBookId" class="form-label">Book ID</label>
                <input type="text" class="form-control" name="deleteBookId" id="deleteBookId" placeholder="Enter book ID to delete" value="<?php echo htmlspecialchars($bookId); ?>" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete Book</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5 py-3 bg-dark text-white">
        All rights reserved by CSE, RUET @2024
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
