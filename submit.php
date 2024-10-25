<?php
// Database connection details
$server = "localhost";
$user = "root";
$password = "";
$db = "project";

// Create connection to MySQL server (without specifying database yet)
$conn = new mysqli($server, $user, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sqlCreateDb = "CREATE DATABASE IF NOT EXISTS $db";
if ($conn->query($sqlCreateDb) === TRUE) {
    echo "Database created or already exists. ";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($db);

// Create table if it doesn't exist
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS project (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sqlCreateTable) === TRUE) {
    echo "Table created or already exists. ";
} else {
    die("Error creating table: " . $conn->error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Validate inputs
if (empty($name) || empty($email) || empty($message)) {
    die("Please fill in all fields.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

// SQL statement to insert data
$sql = "INSERT INTO project (name, email, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $message); // sss means string for each parameter

// Execute and check for success
if ($stmt->execute()) {
    echo "Data inserted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
