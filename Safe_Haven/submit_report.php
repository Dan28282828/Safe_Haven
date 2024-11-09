<?php
// Database connection details
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";      // Default password is empty
$dbname = "safe_haven";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the form
$name = $_POST['name'];
$email = $_POST['email'];
$description = $_POST['description'];

// File upload handling
$filePath = null;
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $targetDir = "uploads/"; // Directory for uploads
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $filePath = $targetDir . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $filePath);
}

// Insert data into the database
$sql = "INSERT INTO reports (name, email, description, file_path) VALUES ('$name', '$email', '$description', '$filePath')";

if ($conn->query($sql) === TRUE) {
    // Display success message and redirect
    echo "<script>
            alert('Your report has been submitted successfully!');
            window.location.href = 'report.html';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
