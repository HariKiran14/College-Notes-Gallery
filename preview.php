<?php
// Include database connection
require_once('db.php');

// Get the file path from the query string
$file_path = $_GET['file'] ?? '';

if ($file_path) {
    // Sanitize the file path to prevent directory traversal attacks
    $file_path = basename($file_path);

    // Get the full path of the file
    $file = 'uploads/' . $file_path; // Adjust this path as necessary

    // Check if file exists
    if (file_exists($file)) {
        // Serve the file (you may need to adjust headers for different file types)
        $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        switch ($file_extension) {
            case 'pdf':
                header('Content-Type: application/pdf');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'doc':
            case 'docx':
                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                break;
            case 'ppt':
            case 'pptx':
                header('Content-Type: application/vnd.ms-powerpoint');
                break;
            default:
                header('Content-Type: application/octet-stream');
                break;
        }
        header('Content-Disposition: inline; filename="' . $file_path . '"');
        readfile($file);
        exit;
    } else {
        echo 'File not found.';
    }
} else {
    echo 'Invalid file.';
}

// Close database connection
$conn->close();
?>
