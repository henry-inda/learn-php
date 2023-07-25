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

// Function to retrieve income data
function getIncomeData($db_conn)
{
    $query = "SELECT source, amount, date FROM Income WHERE UserID = :user_id";
    $stmt = $db_conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to retrieve expenditure data
function getExpenditureData($db_conn)
{
    $query = "SELECT date, particulars, amount_spent, category FROM Expenditure WHERE UserID = :user_id";
    $stmt = $db_conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to retrieve budget data
function getBudgetData($db_conn)
{
    $query = "SELECT description, amount FROM Ex_Category WHERE UserID = :user_id";
    $stmt = $db_conn->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get income, expenditure, and budget data
$incomeData = getIncomeData($db_conn);
$expenditureData = getExpenditureData($db_conn);
$budgetData = getBudgetData($db_conn);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Personal Finance Manage - Reports</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Reports</h2>

        <h3>Income Report:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incomeData as $income) : ?>
                    <tr>
                        <td><?php echo $income['source']; ?></td>
                        <td>$<?php echo number_format($income['amount'], 2); ?></td>
                        <td><?php echo $income['date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Expenditure Report:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Particulars</th>
                    <th>Amount Spent</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expenditureData as $expenditure) : ?>
                    <tr>
                        <td><?php echo $expenditure['date']; ?></td>
                        <td><?php echo $expenditure['particulars']; ?></td>
                        <td>$<?php echo number_format($expenditure['amount_spent'], 2); ?></td>
                        <td><?php echo $expenditure['category']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Budget Analysis:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Budgeted Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($budgetData as $budget) : ?>
                    <tr>
                        <td><?php echo $budget['description']; ?></td>
                        <td>$<?php echo number_format($budget['amount'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Curve Graphs -->
        <div id="incomeCurveGraph" style="height: 300px;"></div>
        <div id="expenditureCurveGraph" style="height: 300px;"></div>

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

    <!-- Load Google Charts -->
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function formatDate(dateString) {
        // Convert the PHP date format to JavaScript Date format (YYYY-MM-DD)
        var parts = dateString.split('-');
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function drawCharts() {
        var incomeData = google.visualization.arrayToDataTable([
            ['Date', 'Amount'],
            <?php foreach ($incomeData as $income) : ?>
                [formatDate('<?php echo $income['date']; ?>'), <?php echo $income['amount']; ?>],
            <?php endforeach; ?>
        ]);

        var expenditureData = google.visualization.arrayToDataTable([
            ['Date', 'Amount'],
            <?php foreach ($expenditureData as $expenditure) : ?>
                [formatDate('<?php echo $expenditure['date']; ?>'), <?php echo $expenditure['amount_spent']; ?>],
            <?php endforeach; ?>
        ]);

        var incomeOptions = {
            title: 'Income Curve Graph',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: {
                format: 'MMM yyyy' // Format the x-axis label as Month and Year (e.g., Jan 2023)
            }
        };

        var expenditureOptions = {
            title: 'Expenditure Curve Graph',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: {
                format: 'MMM yyyy' // Format the x-axis label as Month and Year (e.g., Jan 2023)
            }
        };

        var incomeChart = new google.visualization.LineChart(document.getElementById('incomeCurveGraph'));
        incomeChart.draw(incomeData, incomeOptions);

        var expenditureChart = new google.visualization.LineChart(document.getElementById('expenditureCurveGraph'));
        expenditureChart.draw(expenditureData, expenditureOptions);
    }
</script>

</body>

</html>
