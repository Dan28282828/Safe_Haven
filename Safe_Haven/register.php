<?php
header('Content-Type: application/json');


error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "safe_haven";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Get the data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['username'], $data['password'], $data['fullName'], $data['address'], $data['birthdate'], $data['age'], $data['gender'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit();
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO users (fullName, address, birthdate, age, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ssissss", $data['fullName'], $data['address'], $data['birthdate'], $data['age'], $data['gender'], $data['username'], $data['password']);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>