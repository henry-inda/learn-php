<?php
// Database credentials
$db_host = 'localhost';     // Change this to your database host
$db_name = 'pfund_db';      // Change this to your database name
$db_user = 'root'; // Change this to your database username
$db_pass = ''; // Change this to your database password

// Establish a connection to the database
try {
    $db_conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
