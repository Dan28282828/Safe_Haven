<?php
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "safe_haven";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Get the data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is valid
if (!isset($data['username'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit();
}

// Prepare the statement
$stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $data['username']);
$stmt->execute();
$stmt->store_result();

// Check if the username exists
if ($stmt->num_rows > 0) {
    $stmt->bind_result($storedPassword, $role);
    $stmt->fetch();

    // Verify the password
    if ($data['password'] === $storedPassword) {
        echo json_encode(['success' => true, 'is_admin' => $role === 'admin']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username not found.']);
}

$stmt->close();
$conn->close();
?>