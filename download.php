<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once('db.php');

// Check if file parameter is set
if(isset($_GET['file'])) {
    $file_path = urldecode($_GET['file']);
    
    // Validate file path
    if(file_exists($file_path)) {
        // Get the file's content type
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $content_type = finfo_file($file_info, $file_path);
        finfo_close($file_info);

        // Set headers for downloading the file
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment; filename=' . basename($file_path));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid request.";
}
?>
