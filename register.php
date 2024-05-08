<?php

require_once 'config.php';  // Include the config file

// Create a new database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from POST
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$verification = $_POST['verification'];

// Using regular expression to check if the answer contains 'five' or '5'
if (!preg_match('/\b(five|5)\b/i', $verification)) {
    // Redirect to a rejected page if the answer is wrong
    header("Location: rejected.html");
    exit();
}

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// SQL to insert new user
$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashedPassword, $email);


// Execute the statement
if ($stmt->execute()) {
    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to a different page upon successful registration
    header("Location: success.html");
    exit();  // Make sure no further script is run after redirection
} else {
    echo "Error: " . $stmt->error;
    // Close statement and connection
    $stmt->close();
    $conn->close();
}

?>