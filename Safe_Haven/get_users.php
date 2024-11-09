<?php
$servername = "localhost";
$username = "root"; 
$password = "";  
$dbname = "safe_haven"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all users from the database
$sql = "SELECT id, fullName, username, role FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($users);

$conn->close();
?>