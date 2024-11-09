<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch report data
$sql = "SELECT id, name, email, description, file_path, created_at FROM reports ORDER BY created_at DESC";
$result = $conn->query($sql);

$reports = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($reports);

$conn->close();
?>
