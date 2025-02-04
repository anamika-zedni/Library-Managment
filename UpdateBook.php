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
$bookTitle = "";
$bookAuthor = "";
$bookPrice = "";
$bookStatus = "";

// Check if a Book ID is passed in the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $bookId = $_GET['id'];

    // Fetch the book details from the database
    $sql = "SELECT title, author, price, status FROM Books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

  

    if ($result->num_rows > 0) {
        // Populate the form fields with book data
        $book = $result->fetch_assoc();
        $bookTitle = $book['title'];
        $bookAuthor = $book['author'];
        $bookPrice = $book['price'];
        $bookStatus = $book['status'];
    } else {
        $message = "Book with ID $bookId not found.";
    }

    // Close the statement
    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $bookId = $_POST['updateBookId'];
    $bookTitle = $_POST['updateBookTitle'];
    $bookAuthor = $_POST['updateBookAuthor'];
    $bookPrice = $_POST['updateBookPrice'];
    $bookStatus = $_POST['updateBookStatus'];

    // Validate input fields
    if (empty($bookTitle) || empty($bookAuthor) || empty($bookPrice) || empty($bookStatus)) {
        $message = "All fields are required.";
    } else {
        // Update the book details
        $sql = "UPDATE Books SET title = ?, author = ?, price = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $bookTitle, $bookAuthor, $bookPrice, $bookStatus, $bookId);

        if ($stmt->execute()) {
            $message = "Book updated successfully!";
        } else {
            $message = "Error updating book: " . $stmt->error;
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
    <title>Update Book</title>
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
                    <li class="nav-item"><a class="nav-link active" href="UpdateBook.php">Update Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="DeleteBook.php">Delete Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Update Book Page -->
    <div class="container mt-5">
        <h2>Update Book</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="UpdateBook.php" method="post">
            <div class="mb-3">
                <label for="updateBookId" class="form-label">Book ID</label>
                <input type="number" min="1" class="form-control" id="updateBookId" name="updateBookId" value="<?php echo htmlspecialchars($bookId); ?>" >
            </div>
            <div class="mb-3">
                <label for="updateBookTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="updateBookTitle" name="updateBookTitle" value="<?php echo htmlspecialchars($bookTitle); ?>" required>
            </div>
            <div class="mb-3">
                <label for="updateBookAuthor" class="form-label">Author</label>
                <input type="text" class="form-control" id="updateBookAuthor" name="updateBookAuthor" value="<?php echo htmlspecialchars($bookAuthor); ?>" required>
            </div>
            <div class="mb-3">
                <label for="updateBookPrice" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="updateBookPrice" name="updateBookPrice" value="<?php echo htmlspecialchars($bookPrice); ?>" required>
            </div>
            <div class="mb-3">
                <label for="updateBookStatus" class="form-label">Status</label>
                <select class="form-select" id="updateBookStatus" name="updateBookStatus" required>
                    <option value="">Select Status</option>
                    <option value="Available" <?php if ($bookStatus == 'Available') echo 'selected'; ?>>Available</option>
                    <option value="Issued" <?php if ($bookStatus == 'Issued') echo 'selected'; ?>>Issued</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Book</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
