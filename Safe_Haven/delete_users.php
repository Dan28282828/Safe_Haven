<?php
header('Content-Type: application/json');

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

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_POST['id'];

    if (!empty($userId)) {
        // Prepare the delete query
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);  // Bind the ID parameter as an integer
        
        // Execute the query and check if the deletion was successful
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting user: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    }
}

$conn->close();
?>