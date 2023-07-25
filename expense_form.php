<?php
// Start the PHP session to maintain user login state
session_start();

// Include the database configuration file
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: auth.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $date = $_POST['date'];
    $particulars = $_POST['particulars'];
    $amount_spent = $_POST['amount_spent'];
    $category = $_POST['category'];

    // Insert expense data into the database
    try {
        $query = "INSERT INTO Expenditure (UserID, date, particulars, amount_spent, category) VALUES (:user_id, :date, :particulars, :amount_spent, :category)";
        $stmt = $db_conn->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':particulars', $particulars);
        $stmt->bindParam(':amount_spent', $amount_spent);
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        // Redirect to dashboard
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        // Display error message if insertion fails
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Personal Finance Manage - Expense Form</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Expense Capture Form</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="particulars">Particulars:</label>
                <input type="text" class="form-control" id="particulars" name="particulars" required>
            </div>
            <div class="form-group">
                <label for="amount_spent">Amount Spent:</label>
                <input type="number" step="0.01" class="form-control" id="amount_spent" name="amount_spent" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php if (isset($error_message)) : ?>
            <div class="text-danger mt-2"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <h2>Navigation Links:</h2>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="income_form.php">Capture Income</a></li>
            <li><a href="expense_form.php">Capture Expenditure</a></li>
            <li><a href="budget_form.php">Capture Budget</a></li>
            <li><a href="reports.php">View Reports</a></li>
            <li><a href="index.php?logout=true">Logout</a></li>
        </ul>
    </div>

    <!-- Add your additional HTML content here -->

    <!-- Add Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
