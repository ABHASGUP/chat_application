<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "chat_db";

echo "<h2>Database Connection Test</h2>";

try {
    // First, connect without database selection
    $conn = mysqli_connect($hostname, $username, $password);
    if(!$conn){
        throw new Exception("Database connection error: " . mysqli_connect_error());
    }
    echo "Connected to MySQL server successfully.<br>";

    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
    if(!mysqli_query($conn, $sql)){
        throw new Exception("Error creating database: " . mysqli_error($conn));
    }
    echo "Database '$dbname' created or already exists.<br>";

    // Select the database
    if(!mysqli_select_db($conn, $dbname)){
        throw new Exception("Error selecting database: " . mysqli_error($conn));
    }
    echo "Selected database '$dbname' successfully.<br>";

    // Create users table
    $users_table = "CREATE TABLE IF NOT EXISTS `users` (
        `user_id` int(11) NOT NULL AUTO_INCREMENT,
        `unique_id` int(255) NOT NULL,
        `fname` varchar(255) NOT NULL,
        `lname` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `img` varchar(255) NOT NULL,
        `status` varchar(255) NOT NULL,
        PRIMARY KEY (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if(!mysqli_query($conn, $users_table)){
        throw new Exception("Error creating users table: " . mysqli_error($conn));
    }
    echo "Users table created or already exists.<br>";

    // Create messages table
    $messages_table = "CREATE TABLE IF NOT EXISTS `messages` (
        `msg_id` int(11) NOT NULL AUTO_INCREMENT,
        `incoming_msg_id` int(255) NOT NULL,
        `outgoing_msg_id` int(255) NOT NULL,
        `msg` varchar(1000) NOT NULL,
        PRIMARY KEY (`msg_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if(!mysqli_query($conn, $messages_table)){
        throw new Exception("Error creating messages table: " . mysqli_error($conn));
    }
    echo "Messages table created or already exists.<br>";

    // Test if tables exist
    $result = mysqli_query($conn, "SHOW TABLES");
    echo "<h3>Existing Tables:</h3>";
    while($row = mysqli_fetch_array($result)){
        echo $row[0] . "<br>";
    }

} catch (Exception $e) {
    die("<br>Error: " . $e->getMessage());
}
?>
