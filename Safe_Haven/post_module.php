<?php
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

// Handle module post form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $mediaPath = '';

    // Handle file upload for images or videos
    if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $mediaPath = $targetDir . basename($_FILES["media"]["name"]);
        if (move_uploaded_file($_FILES["media"]["tmp_name"], $mediaPath)) {
            // File uploaded successfully
        } else {
            echo json_encode(['success' => false, 'message' => 'File upload failed.']);
            exit;
        }
    }

    // Insert the module into the 'modules' table
    $stmt = $conn->prepare("INSERT INTO modules (title, content, media_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $mediaPath);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Module posted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error posting module.']);
    }

    $stmt->close();
}

$conn->close();
?>
