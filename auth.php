<?php
// Start the PHP session to maintain user login state
session_start();

// Include the database configuration file
include 'config.php';

// Function to calculate the total income
function getTotalIncome($db_conn)
{
    $query = "SELECT SUM(amount) AS total_income FROM Income";
    $stmt = $db_conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_income'] ? $result['total_income'] : 0;
}

// Function to calculate the total expenditure
function getTotalExpenditure($db_conn)
{
    $query = "SELECT SUM(amount_spent) AS total_expenditure FROM Expenditure";
    $stmt = $db_conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_expenditure'] ? $result['total_expenditure'] : 0;
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect to the index
    header("Location: index.php");
    exit;
}

// Handle user login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Retrieve login form data
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    // Check if the username exists in the database
    $query = "SELECT UserID, username, password FROM Users WHERE username = :username";
    $stmt = $db_conn->prepare($query);
    $stmt->bindParam(':username', $login_username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($login_password, $user['password'])) {
        // Set user session data
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['username'] = $user['username'];

        // Redirect to dashboard
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Invalid username or password.";
    }
}


// Handle user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Retrieve registration form data
    $register_username = $_POST['register_username'];
    $register_name = $_POST['register_name'];
    $register_email = $_POST['register_email'];
    $register_phone = $_POST['register_phone'];
    $register_password = $_POST['register_password'];

    // Hash the password for security
    $hashedPassword = password_hash($register_password, PASSWORD_DEFAULT);

    // Insert user data into the database
    try {
        $query = "INSERT INTO Users (username, name, email, phone, password) VALUES (:username, :name, :email, :phone, :password)";
        $stmt = $db_conn->prepare($query);
        $stmt->bindParam(':username', $register_username);
        $stmt->bindParam(':name', $register_name);
        $stmt->bindParam(':email', $register_email);
        $stmt->bindParam(':phone', $register_phone);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Registration successful, set user session and redirect to index
        $_SESSION['user_id'] = $db_conn->lastInsertId();
        $_SESSION['username'] = $register_username; // Set the 'username' session variable here

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        // Display error message if registration fails
        $register_error = "Error: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Personal Finance Manage - Login / Signup</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h2>Login</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form-group">
                        <label for="login_username">Username:</label>
                        <input type="text" class="form-control" id="login_username" name="login_username" required>
                    </div>
                    <div class="form-group">
                        <label for="login_password">Password:</label>
                        <input type="password" class="form-control" id="login_password" name="login_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="login">Login</button>
                    <?php if (isset($login_error)) : ?>
                        <div class="text-danger mt-2"><?php echo $login_error; ?></div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Sign Up</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form-group">
                        <label for="register_username">Username:</label>
                        <input type="text" class="form-control" id="register_username" name="register_username" required>
                    </div>
                    <div class="form-group">
                        <label for="register_name">Name:</label>
                        <input type="text" class="form-control" id="register_name" name="register_name" required>
                    </div>
                    <div class="form-group">
                        <label for="register_email">Email:</label>
                        <input type="email" class="form-control" id="register_email" name="register_email" required>
                    </div>
                    <div class="form-group">
                        <label for="register_phone">Phone:</label>
                        <input type="tel" class="form-control" id="register_phone" name="register_phone">
                    </div>
                    <div class="form-group">
                        <label for="register_password">Password:</label>
                        <input type="password" class="form-control" id="register_password" name="register_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="register">Sign Up</button>
                    <?php if (isset($register_error)) : ?>
                        <div class="text-danger mt-2"><?php echo $register_error; ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Add your additional HTML content here -->

    <!-- Add Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
