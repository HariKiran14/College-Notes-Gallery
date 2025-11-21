<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
include 'functions.php'; // Include the file where the getUserDetails function is defined

$userId = $_SESSION['user_id'];
$userDetails = getUserDetails($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .h1-tag{
            font-size: 20px;
            font-family: 'Times New Roman', Times, serif;
            color: white;
        }
        .navbar-custom {
    background-color: #343a40 !important;
    height: 65px;
}

        .navbar a {
            color: #ffffff;
            margin: 0 15px;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
            transition: color 0.3s, padding 0.3s;
        }
        .navbar a:hover {
            color: #007bff;
            text-decoration: none;
            background-color: #e9ecef;
            border-radius: 4px;
            padding: 10px 15px;
        }
        .container {
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #007bff;
        }
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-image {
            width: 200px;
            height: 200px;
            overflow: hidden;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .user-details {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin: 0 auto;
            text-align: left;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .user-details p {
            margin: 10px 0;
            color: #555;
        }
        .dropdown-menu {
        background-color: lightslategray;
    }
        .user-details strong {
            display: inline-block;
            width: 120px;
            color: #333;
            font-weight: bold;
            margin-bottom: 5px;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
        <h1 class="h1-tag">Malnad College Notes Gallery</h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-h"></i> Explore More
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="about_us.html"><i class="fas fa-info-circle"></i> About Us</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="acadmic_portal.html"><i class='fas fa-school'></i> Academic Portal</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" id="logoutLink"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Malnad College Notes Gallery</h5>
            <hr>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
                    <hr>
                </li>
                <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-h"></i> Explore More
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="about_us.html"><i class="fas fa-info-circle"></i> About Us</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="acadmic_portal.html"><i class='fas fa-school'></i> Academic Portal</a></li>
                        </ul>
                    </li>
                    <hr>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" id="logoutLink"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container">
        <h1>My Account</h1>
        <div class="profile-section">
            <div class="profile-image">
                <?php if (!empty($userDetails['profile_image'])) : ?>
                    <img src="<?php echo htmlspecialchars($userDetails['profile_image']); ?>" alt="Profile Image">
                <?php else: ?>
                    <p>No profile image uploaded.</p>
                <?php endif; ?>
            </div>
            <div class="user-details">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($userDetails['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($userDetails['email']); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($userDetails['role']); ?></p>
                <p><strong>Join Date:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($userDetails['join_date']))); ?></p>
            </div>
        </div>
    </div>
    <script>
        // Logout Confirmation
        document.getElementById('logoutLink').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'logout.php';
            }
        });
    </script>
</body>
</html>
