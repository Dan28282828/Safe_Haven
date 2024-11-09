<?php
// Database configuration
$servername = "localhost"; // Change if your database server is different
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "safe_haven"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}   

// Update profile data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['profileFullName'];
    $address = $_POST['profileAddress'];
    $birthdate = $_POST['profileBirthdate'];
    $age = $_POST['profileAge'];
    $gender = $_POST['profileGender'];
    $username = $_POST['profileUsername'];
    $password = $_POST['profilePassword']; // Plain text password

    // Update user in the database
    $update_sql = "UPDATE users SET fullName='$fullName', address='$address', birthdate='$birthdate', age='$age', gender='$gender', password='$password' WHERE username='$username'";

    if ($conn->query($update_sql) === TRUE) {
        echo "Profile updated successfully!";
        header('Location: dashboard.html'); // Change as needed
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>