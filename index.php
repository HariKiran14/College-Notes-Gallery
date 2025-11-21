<?php
// Start session
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include the configuration file
include('config.php');

// Initialize $isFaculty as false by default
$isFaculty = false;

// Check if the user is a faculty member
if (isset($_SESSION["role"]) && $_SESSION["role"] === 'Faculty') {
    $isFaculty = true;
}

// Get the base URL
$baseUrl = getBaseUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Notes</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-image: url('<?php echo $baseUrl; ?>images/home.jpg');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-blend-mode: multiply;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #333;
    }

    .dropdown-menu {
        background-color: lightslategray;
    }

    .navbar {
        width: 100%;
        padding: 0 15px;
        box-sizing: border-box;
    }

    .navbar a {
        color: #ffffff;
        margin: 0 10px;
        text-decoration: none;
        font-size: 16px;
        padding: 10px 15px;
        transition: color 0.3s, background-color 0.3s;
    }

    .navbar a:hover {
        color: #007bff;
        background-color: #e9ecef;
        border-radius: 4px;
    }

    .welcome-text {
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 800px;
        text-align: left;
        font-size: 18px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 1199px) {
        .navbar a {
            font-size: 15px;
        }

        .welcome-text {
            font-size: 16px;
            padding: 15px;
        }
    }

    @media (max-width: 991px) {
        .navbar a {
            font-size: 14px;
            margin: 0 8px;
        }

        .welcome-text {
            font-size: 14px;
            padding: 15px;
        }

        .offcanvas-header h5 {
            font-size: 16px;
            background-color: #CD853F;
        }

        .offcanvas-body {
            background-color: #CD853F;
        }
    }

    @media (max-width: 767px) {
        .navbar-toggler {
            margin-right: 5px;
        }

        .offcanvas-body {
            padding: 10px;
            background-color: #CD853F;
        }

        .offcanvas-header {
            padding: 10px;
            background-color: #CD853F;
        }

        .welcome-text {
            font-size: 12px;
            padding: 10px;
        }
    }

    @media (max-width: 576px) {
        .navbar a {
            font-size: 12px;
            margin: 0 5px;
        }

        .welcome-text {
            font-size: 10px;
            padding: 5px;
        }
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top">
        <div class="container-fluid">
            <h1 style="color:white; font-size:35px; font-family:italic;padding: 10px 20px;">Malnad College Notes Gallery</h1>
            <button class="navbar-toggler shadow-none-border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <?php if ($isFaculty): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="upload_note.php">Upload Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="uploaded_notes.php">My Uploaded Notes</a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="display_notes.php">Display Notes</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Explore More
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="about_us.html">About</a></li>
                                <hr>
                                <li><a class="dropdown-item" href="acadmic_portal.html">Academic Portal</a></li>
                                <hr>
                                <li><a class="dropdown-item" href="my_account.php">My Account</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="confirmLogout(event)">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="welcome-text">
        <p>Welcome to the Malnad College Notes Gallery! Established to support student's academic journeys, our platform offers a centralized repository of study materials. With an extensive collection of notes organized by year and subject, finding the right materials is made easy with our advanced search functionality.</p>
        <p>The College Notes Gallery also encourages student engagement, allowing you to upload and share your own notes. Supported by robust technological infrastructure, including Computer Centers and Internet access, the platform bridges traditional library resources with digital accessibility.</p>
    </div>

    <script>
    function confirmLogout(event) {
        event.preventDefault(); // Prevent the default action
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = 'logout.php'; // Redirect to logout page if confirmed
        }
    }
    </script>
</body>
</html>
