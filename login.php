<?php
// Start session to hold user data upon successful login
session_start();

require_once 'config.php';  // Include the config file

// Create a new database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username and password from POST data
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to fetch user details
$sql = "SELECT id, username, password FROM users WHERE username = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);

// Execute the statement
$stmt->execute();

// Bind result variables
$stmt->bind_result($id, $fetchedUsername, $hashedPassword);

// Fetch values
if ($stmt->fetch()) {
    // Verify the password with the hashed password in the database
    if (password_verify($password, $hashedPassword)) {
        // Set session variables
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $fetchedUsername;

        // Redirect to a new page or dashboard
        header("Location: welcome.html");
        exit();
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "No user found with that username.";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
