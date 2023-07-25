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

// Function to calculate the total income
function getTotalIncome($db_conn)
{
    $query = "SELECT SUM(amount) AS total_income FROM Income WHERE UserID = :user_id";
    $stmt = $db_conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_income'] ? $result['total_income'] : 0;
}

// Function to calculate the total expenditure
function getTotalExpenditure($db_conn)
{
    $query = "SELECT SUM(amount_spent) AS total_expenditure FROM Expenditure WHERE UserID = :user_id";
    $stmt = $db_conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_expenditure'] ? $result['total_expenditure'] : 0;
}

// Get the total income and total expenditure
$totalIncome = getTotalIncome($db_conn);
$totalExpenditure = getTotalExpenditure($db_conn);

// Handle user logout
if (isset($_GET['logout'])) {
    // Clear the session data and redirect to login page
    session_unset();
    session_destroy();
    header("Location: auth.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Personal Finance Manage - Dashboard</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p>Here's a summary of your financial data:</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Income</td>
                    <td>$<?php echo number_format($totalIncome, 2); ?></td>
                </tr>
                <tr>
                    <td>Total Expenditure</td>
                    <td>$<?php echo number_format($totalExpenditure, 2); ?></td>
                </tr>
                <tr>
                    <td>Net Savings</td>
                    <td>$<?php echo number_format($totalIncome - $totalExpenditure, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <h2>Navigation Links:</h2>
        <ul>
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
