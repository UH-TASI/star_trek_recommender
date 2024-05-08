<?php

session_start();  // Start the session to access session variables
require_once 'config.php';  // Include the config file

// Create a new database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];  // Ensure the user is logged in and you have their user ID
$episode_id = $_POST['episode_id'];  // The ID of the episode being rated
$rating = $_POST['rating'];  // The rating provided by the user

$sql = "INSERT INTO ratings (user_id, episode_id, rating) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $episode_id, $rating);

if ($stmt->execute()) {
    header("Location: episode_rating.php");
    exit();
} else {
    echo "Error submitting rating: " . $conn->error;
}

$stmt->close();