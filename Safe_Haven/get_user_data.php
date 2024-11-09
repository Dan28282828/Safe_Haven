<?php
// Database configuration
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

// Get user data
if (isset($_GET['username'])) {
    $user_username = $_GET['username'];
    $sql = "SELECT * FROM users WHERE username='$user_username'";   
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        echo json_encode($userData);
    } else {
        echo json_encode(["error" => "User not found."]);
    }
}

$conn->close();
?>