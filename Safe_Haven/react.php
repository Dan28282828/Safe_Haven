<?php
session_start(); // Start the session to access user information

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert reaction into the 'reactions' table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleId = $_POST['module_id'];
    $reaction = $_POST['reaction'];
    $userId = 1; // Placeholder for user ID, replace with actual user ID in a session

    $stmt = $conn->prepare("INSERT INTO reactions (module_id, user_id, reaction) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $moduleId, $userId, $reaction);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reaction recorded successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error recording reaction.']);
    }

    $stmt->close();
}

$conn->close();
?>
