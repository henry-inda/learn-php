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

// Handle income form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $income_source = $_POST['income_source'];
    $income_amount = $_POST['income_amount'];
    $income_date = $_POST['income_date'];
    $income_narration = $_POST['income_narration'];

    // Insert income data into the database
    try {
        // Modify the query to include the UserID column
        $query = "INSERT INTO Income (UserID, source, amount, date, details) VALUES (:user_id, :source, :amount, :date, :details)";
        $stmt = $db_conn->prepare($query);
        
        // Bind the UserID value from the session
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        
        $stmt->bindParam(':source', $income_source);
        $stmt->bindParam(':amount', $income_amount);
        $stmt->bindParam(':date', $income_date);
        $stmt->bindParam(':details', $income_narration);
        $stmt->execute();

        // Redirect to the dashboard after successful income capture
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        // Display error message if income capture fails
        $income_error = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Personal Finance Manage - Income Capture Form</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Income Capture Form</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="income_source">Source:</label>
                <input type="text" class="form-control" id="income_source" name="income_source" required>
            </div>
            <div class="form-group">
                <label for="income_amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" id="income_amount" name="income_amount" required>
            </div>
            <div class="form-group">
                <label for="income_date">Date:</label>
                <input type="date" class="form-control" id="income_date" name="income_date" required>
            </div>
            <div class="form-group">
                <label for="income_narration">Narration:</label>
                <textarea class="form-control" id="income_narration" name="income_narration" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Capture Income</button>
            <?php if (isset($income_error)) : ?>
                <div class="text-danger mt-2"><?php echo $income_error; ?></div>
            <?php endif; ?>
        </form>
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
