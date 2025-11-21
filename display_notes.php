<?php
// Include database connection
require_once('db.php');

// Fetch search criteria from the form
$year = $_GET['year'] ?? '';
$subject = $_GET['subject'] ?? '';
$title = $_GET['title'] ?? '';

// Prepare SQL query with optional filters
$sql = "SELECT * FROM notes WHERE 1=1";

$params = [];
$types = '';

if ($year) {
    $sql .= " AND year = ?";
    $params[] = $year;
    $types .= 'i';
}
if ($subject) {
    $sql .= " AND subject LIKE ?";
    $params[] = "%$subject%";
    $types .= 's';
}
if ($title) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$title%";
    $types .= 's';
}

$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Notes Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Common Styles */
        body {
            font-family: Arial, sans-serif;
        }
        .h1-tag{
            font-size: 20px;
            font-family: 'Times New Roman', Times, serif;
            color: white;
        }

        .navbar-custom {
            background-color: #B4B4B8 !important;
            height: 95px;
        }

        .navbar-brand-custom {
            font-size: 2.8em;
            font-weight: bold;
        }

        .nav-item {
            color: black;
        }

        .dform {
            width: 100%;
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            position: relative;
            top: 101px;
           
        }

        .navbar {
            width: 100%;
            padding: 0 15px;
            box-sizing: border-box;
        }

        .navbar a {
            color: black;
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
            text-decoration: none;
        }

        .dform form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
          
        }

        .dform label {
            font-weight: bold;
        }

        .navbar-nav {
            position: relative;
            left: 800px;
            text-decoration: none;
        }

        .container {
            position: relative;
            top: 80px;
        }

        .submit {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .submit button {
            padding: 10px 20px;
            background-color: #3D3B40;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit button:hover {
            background-color: #45a049;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .preview-button, .download-button {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .preview-button {
            background-color: #007bff;
        }

        .download-button {
            background-color: #28a745;
        }

        .preview-button:hover {
            background-color: #0056b3;
        }

        .download-button:hover {
            background-color: #218838;
        }

        .fa-eye, .fa-download {
            margin-right: 5px;
        }

        .table-responsive {
            overflow-y: auto;
            height: 80vh;
        }

        .table-responsive::-webkit-scrollbar {
            width: 12px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .dropdown-menu {
        background-color: lightslategray;
    }

        /* Desktop Styles */
        @media (min-width: 768px) {
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                font-size: 16px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 10px;
            }

            th {
                background-color: #f4f4f4;
                text-align: left;
            }
        }

        /* Mobile Styles */
        @media (max-width: 767px) {
            .dform form {
                flex-direction: column;
              
            }

            .navbar-nav {
                left: 0;
                text-align: center;
            }
            .table-responsive {
            overflow-y: auto;
            height: 150vh;
        }

            .dropdown-menu {
                background-color: #AEB6BF;
            }

            th, td {
                font-size: 10px;
                padding: 2px;
            }

            table {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
<div class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top navbar-custom">
    <div class="container-fluid">
    <h1 class="h1-tag">Malnad College Notes Gallery</h1>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel"> Malnad College Notes Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a href="index.php" class="nav-link">Home</a>

                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Explore More
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="about_us.html">About Us</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="acadmic_portal.html">Academic Portal</a></li>
                            <hr>
                        <li><a class="dropdown-item" href="my_account.php">My Account</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" id="logoutLink">Logout</a>
                    
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="dform">
    <form method="GET" action="display_notes.php">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" class="form-control">
        <label for="year">Year:</label>
        <select id="year" name="year" class="form-select">
            <option value="">Select Year</option>
            <option value="2">2nd Year</option>
            <option value="3">3rd Year</option>
            <option value="4">4th Year</option>
        </select>
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" class="form-control">
        <div class="submit"><button type="submit" class="btn btn-primary">Search</button></div>
    </form>
</div>

<?php
if ($result->num_rows > 0) {
    echo '<div class="container">';
    echo '<h2>Download Notes</h2>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Title</th>';
    echo '<th>Subject</th>';
    echo '<th>Description</th>';
    echo '<th>Preview</th>';
    echo '<th>Download</th>';
    echo '<th>Uploaded At</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        $note_id = $row['note_id'];
        $title = htmlspecialchars($row['title']);
        $subject = htmlspecialchars($row['subject']);
        $description = htmlspecialchars($row['description']);
        $file_path = htmlspecialchars($row['file_path']);
        $uploaded_at = htmlspecialchars($row['uploaded_at']);

        echo '<tr>';
        echo '<td>' . $title . '</td>';
        echo '<td>' . $subject . '</td>';
        echo '<td>' . $description . '</td>';
        echo '<td><a class="preview-button" href="preview.php?file=' . $file_path . '" target="_blank"><i class="fa fa-eye"></i> Preview</a></td>';
        echo '<td><a class="download-button" href="download.php?file=' . $file_path . '"><i class="fa fa-download"></i> Download</a></td>';
        echo '<td>' . $uploaded_at . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<div class="container"><p>No notes found.</p></div>';
}

// Close database connection
$conn->close();
?>

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

