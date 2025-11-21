<?php
// Start session
session_start();

// Check if the user is logged in and is faculty
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'Faculty') {
    header("location: login.php");
    exit;
}

// Include database connection
require_once('db.php');

// Function to fetch and display notes
function displayNotes($conn, $user_id) {
    $sql = "SELECT note_id, title, subject, description, uploaded_at, file_path FROM notes WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $notes = $result->fetch_all(MYSQLI_ASSOC);

                echo "<table class='table table-bordered table-responsive'>";
                echo "<thead class='thead-dark'>";
                echo "<tr>";
                echo "<th>Title</th>";
                echo "<th>Subject</th>";
                echo "<th>Description</th>";
                echo "<th>Uploaded At</th>";
                echo "<th>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                foreach ($notes as $note) {
                    echo "<tr id='note-".$note['note_id']."'>";
                    echo "<td>" . htmlspecialchars($note['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($note['subject']) . "</td>";
                    echo "<td>" . htmlspecialchars($note['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($note['uploaded_at']) . "</td>";
                    echo "<td>
                            <i class='fa fa-eye sprite-icon preview-note' data-file-path='".htmlspecialchars($note['file_path'])."'></i>
                            <a href='download.php?file=".urlencode($note['file_path'])."'><i class='fa fa-download sprite-icon'></i></a>
                          </td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>You haven't uploaded any notes yet.</p>";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }
}

// HTML and PHP code to display notes
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Notes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 15px;
            transition: color 0.3s, background-color 0.3s;
        }
        .navbar a:hover {
            color: #007bff;
            background-color: #ADD8E6;
            border-radius: 4px;
            text-decoration: none;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .dropdown-menu {
            background-color: lightslategray;
        }
        .notes-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .sprite-icon {
            cursor: pointer;
            padding: 5px;
            transition: transform 0.3s;
        }
        .sprite-icon:hover {
            transform: scale(1.1);
        }
        iframe {
            width: 100%;
            height: 500px;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }
            .dropdown-menu {
                background-color: #AEB6BF;
            }
            .notes-container {
                padding: 10px;
            }
            .navbar a {
                font-size: 14px;
            }
               .notes-table th, .notes-table td {
                font-size: 12px;
                padding: 6px;
            }
            .table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Malnad College Notes Gallery</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="upload_note.php"><i class="fa fa-upload"></i> Upload Notes</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Explore More
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="about_us.html">About Us</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="acadmic_portal.html">Academic Portal</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="my_account.php">My Account</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php" id="logoutLink"><i class="fa fa-sign-out"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="notes-container">
    <h1>Your Uploaded Notes</h1>
    <div class="content">
        <?php displayNotes($conn, $_SESSION["user_id"]); ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('logoutLink').addEventListener('click', function(event) {
        if (!confirm('Are you sure you want to logout?')) {
            event.preventDefault();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.preview-note').forEach(function(element) {
            element.addEventListener('click', function() {
                var filePath = this.getAttribute('data-file-path');
                var fileExtension = filePath.split('.').pop().toLowerCase();
                var viewerUrl = '';

                switch(fileExtension) {
                    case 'pdf':
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                        viewerUrl = filePath;
                        break;
                    case 'doc':
                    case 'docx':
                    case 'ppt':
                    case 'pptx':
                    case 'xls':
                    case 'xlsx':
                        viewerUrl = 'https://docs.google.com/gview?url=' + encodeURIComponent(window.location.origin + '/' + filePath) + '&embedded=true';
                        break;
                    default:
                        alert('Preview not available for this file type.');
                        return;
                }

                window.open(viewerUrl, '_blank');
            });
        });
    });
</script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
