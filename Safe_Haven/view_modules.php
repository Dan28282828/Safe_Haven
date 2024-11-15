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

// Fetch all modules from the database
$result = $conn->query("SELECT * FROM modules ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Haven</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Import fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        * {
            padding: 0;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        nav {
            width: 100%;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            margin-bottom: 20px;
            position: relative;
        }

        .nav-container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo span {
            font-size: 1.5rem;
            font-weight: bold;
            color: #03f554;
        }

        .links a {
            font-size: 1.1rem;
            color: #333;
            margin: 0 15px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #03f554;
        }

        .hamburg { 
            position: relative;
            display: inline-block;
            cursor: pointer;
            color: #0e0d0d;
            font-size: 3rem;
            padding: 15px;
        }

        .hamburg-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: rgba(252, 252, 252, 0.8);
            min-width: 150px;
            border-radius: 5px;
            box-shadow: 0 8px 16px rgba(20, 18, 18, 0.2);
            z-index: 1;
        }

        .hamburg-dropdown a {
            color: rgb(19, 39, 23);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 1rem;
        }

        .hamburg-dropdown a:hover {
            background-color: #03f554;
        }

        @media (max-width: 768px) {
            .links {
                display: none; /* Hide the normal links on mobile */
            }

            .hamburg {
                display: inline-block; /* Show hamburger icon on mobile */
            }
        }

        .container {
            width: 90%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px; /* Add some padding */
            margin: 20px 0; /* Add margin for spacing */
            max-height: calc(100vh - 120px); /* Adjust max-height based on nav height */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        .module {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .module h3 {
            font-size: 1.4rem;
            color: #03a14a;
            margin-bottom: 10px;
        }

        .module p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        img, video {
            margin-top: 10px;
            border-radius: 4px;
            max-width: 100%;
            height: auto;
        }

        button {
            padding: 5px 15px;
            background-color: #03f554;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #02c543;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <span>Safe Haven</span>
            </div>
            <div class="links">
                <a href="dashboard.html">Home</a>
                <a href="view_modules.php">Modules</a>
                <a href="report.html">Report</a>
                <a href="emergency.html">Emergency</a>
            </div>
            <div class="hamburg">
                <i class="fas fa-bars" id="hamburgButton"></i>
                <div id="hamburgDropdown" class="hamburg-dropdown">
                    <a href="edit_profile.html">Edit Profile</a>
                    <a href="#" id="logoutBtn">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Modules</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='module'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['content']) . "</p>";

                // Display media if available
                if ($row['media_path']) {
                    $mediaPath = htmlspecialchars($row['media_path']);
                    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $mediaPath)) {
                        echo "<img src='$mediaPath' alt='Module Media'>";
                    } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $mediaPath)) {
                        echo "<video controls>
                                <source src='$mediaPath' type='video/mp4'>
                              </video>";
                    }
                }

                echo "<button onclick='react(" . $row['id'] . ", \"like\")'>Like</button>";
                echo "<button onclick='react(" . $row['id'] . ", \"dislike\")'>Dislike</button>";
                echo "</div>";
            }
        } else {
            echo "<p>No modules found.</p>";
        }

        $conn->close();
        ?>
    </div>

    <script>
    function react(moduleId, reaction) {
        fetch('react.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `module_id=${moduleId}&reaction=${reaction}`
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('logoutBtn').addEventListener('click', function() {
        alert('You have logged out.');
        window.location.href = 'login.html';
    });

    document.getElementById('hamburgButton').addEventListener('click', function() {
        const dropdown = document.getElementById('hamburgDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    window.onclick = function(event) {
        if (!event.target.matches('#hamburgButton')) {
            const dropdowns = document.getElementsByClassName("hamburg-dropdown");
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.style.display === 'block') {
                    openDropdown.style.display = 'none';
                }
            }
        }
    }
    </script>
</body>
</html>
