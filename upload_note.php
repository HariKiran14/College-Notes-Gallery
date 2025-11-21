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

// Define variables and initialize with empty values
$title = $subject = $description = $year = "";
$title_err = $subject_err = $description_err = $year_err = $file_err = "";

// Process form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate year
    if (empty(trim($_POST["year"]))) {
        $year_err = "Please select the course year.";
    } else {
        $year = trim($_POST["year"]);
    }

    // Validate subject
    if (empty(trim($_POST["subject"]))) {
        $subject_err = "Please enter the subject.";
    } else {
        $subject = trim($_POST["subject"]);
    }

    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter the description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate file upload
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $fileName = basename($_FILES["file"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowedTypes = array(
            "jpg", "jpeg", "png", "pdf", "doc", "docx", "ppt", "pptx",
            "mp4", "mkv", "avi", "mov",
            "mp3", "wav", "aac",
            "zip", "rar",
            "html", "css", "json", "c", "java", "js",
            "*" // Allow all other file types
        );

        if (!in_array($fileType, $allowedTypes)) {
            $file_err = "Only specific file types are allowed.";
        } else {
            // Check file size (limit to 100MB)
            if ($_FILES["file"]["size"] > 133000000) {
                $file_err = "File size exceeds the 100MB limit.";
            } else {
                // Move uploaded file to the target directory
                $targetDir = "uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $targetFilePath = $targetDir . $fileName;
                if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                    $file_err = "There was an error uploading your file.";
                }
            }
        }
    } else {
        $file_err = "Please select a file to upload.";
    }

    // Check input errors before inserting in database
    if (empty($title_err) && empty($year_err) && empty($subject_err) && empty($description_err) && empty($file_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO notes (user_id, title, year, subject, description, file_path) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("isssss", $param_user_id, $param_title, $param_year, $param_subject, $param_description, $param_file_path);

            // Set parameters
            $param_user_id = $_SESSION["user_id"];
            $param_title = $title;
            $param_year = $year;
            $param_subject = $subject;
            $param_description = $description;
            $param_file_path = $targetFilePath;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to uploaded notes page
                header("location: uploaded_notes.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "SQL error: " . $conn->error;
        }
    }

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Notes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
       /* Base Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.navbar {
    background-color: #343a40; /* Background color */

}

.navbar-dark .navbar-brand {
    color: white; /* Navbar brand color */
}

.navbar-dark .navbar-nav .nav-link {
    color:  #aeb6bf ; /* Navbar link color */
    font-size: 15px;
  
}

.navbar-dark .navbar-nav .nav-link:hover {
    color: #007bff;
    background-color: #e9ecef;
    border-radius: 4px;
}

.wrapper {
    width: 90%;
    max-width: 600px;
    background-color: #ffffff;
    margin: 20px auto;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Increased shadow */
    border: 1px solid #dee2e6; /* Added border for contrast */
}

h2 {
    margin-bottom: 15px;
    color: #333;
    font-size: 28px;
    text-align: center;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1); /* Enhanced inner shadow */
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25); /* Focus outline shadow */
}

.btn-primary {
    background-color: #007bff;
    border: none;
    color: #fff;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background-color 0.3s, box-shadow 0.3s; /* Smooth transitions */
}

.btn-primary:hover {
    background-color: #0056b3;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
}

.has-error .form-control {
    border-color: red;
}

.error {
    color: red;
    font-size: 12px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.dropdown-menu {
    background-color: lightslategray;
}

.form-row label {
    width: 100%;
    padding-right: 10px;
    color: #333;
    font-size: 14px;
    box-sizing: border-box;
}

.form-row .form-control {
    flex: 1;
    width: 100%;
}

h1 {
    text-align: center;
    color: black;
    margin-bottom: 20px;
    font-size: 30px;
}
.navbar-nav {
    align-items: center; /* Center items vertically */
    margin-left: auto;
}

/* Media Queries for responsiveness */
@media (max-width: 767px) {
    .navbar-nav .nav-link {
        color: black; /* Ensure text color for small screens */
        font-size: 14px; /* Adjust font size if needed */
    }

    .navbar-dark .navbar-nav .nav-link:hover {
        color: #007bff;
    }
    
    h1 {
        font-size: 20px;
    }

    .wrapper {
        width: 90%;
        padding: 20px;
    }

    .form-row label {
        width: 100%;
        margin-bottom: 5px;
    }
    .navbar-dark .navbar-nav .nav-link {
    color:  black ; /* Navbar link color */
    font-size: 15px;
    margin-left: 0 auto;
  
}
}

@media (max-width: 479px) {
    .form-control {
        width: 100%;
        padding: 8px;
        font-size: 14px;
    }
   
.navbar-dark .navbar-nav .nav-link {
    color:  black ; /* Navbar link color */
    font-size: 15px;
    margin-left: 0 auto;
  
}

  
}

    </style>
</head>
<body>
    <!-- Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Malnad College Notes Gallery</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Malnad College Notes Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="uploaded_notes.php"><i class="fa-solid fa-file-arrow-up"></i> My Uploaded Notes</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="offcanvasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-h"></i> Explore More
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="offcanvasDropdown">
                                <li><a class="dropdown-item" href="about_us.html"><i class='fas fa-info-circle'></i> About Us</a></li>
                                <hr>
                                <li><a class="dropdown-item" href="acadmic_portal.html"><i class='fas fa-school'></i> Academic Portal</a></li>
                                <hr>
                                <li><a class="dropdown-item" href="my_account.php"><i class="fas fa-user"></i> My Account</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php" id="logoutLink"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <div class="content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <h2>Upload New Note</h2>
                <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                    <div class="form-row">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                    </div>
                    <span class="error"><?php echo $title_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($subject_err)) ? 'has-error' : ''; ?>">
                    <div class="form-row">
                        <label for="subject">Subject:</label>
                        <input type="text" name="subject" id="subject" class="form-control" value="<?php echo htmlspecialchars($subject); ?>" required>
                    </div>
                    <span class="error"><?php echo $subject_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                    <div class="form-row">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($description); ?></textarea>
                    </div>
                    <span class="error"><?php echo $description_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($year_err)) ? 'has-error' : ''; ?>">
                    <div class="form-row">
                        <label for="year">Course Year:</label>
                        <select name="year" id="year" class="form-control" required>
                            <option value="" <?php if(empty($year)) echo 'selected'; ?>>Select course Year</option>
                            <option value="2" <?php if($year == '2') echo 'selected'; ?>>2nd Year</option>
                            <option value="3" <?php if($year == '3') echo 'selected'; ?>>3rd Year</option>
                            <option value="4" <?php if($year == '4') echo 'selected'; ?>>4th Year</option>
                        </select>
                    </div>
                    <span class="error"><?php echo $year_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($file_err)) ? 'has-error' : ''; ?>">
                    <div class="form-row">
                        <label for="file">File:</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                    <span class="error"><?php echo $file_err; ?></span>
                </div>
                <input type="submit" value="Upload" class="btn-primary">
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('logoutLink').addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to logout?')) {
                event.preventDefault(); // Stops the navigation if the user clicks "Cancel"
            }
        });
    </script>
</body>
</html>
