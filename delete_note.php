<?php
// Start session
session_start();

// Check if the user is logged in and is faculty
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'Faculty') {
    echo 'error';
    exit;
}

// Check if the note_id is set in the POST request
if (isset($_POST['note_id'])) {
    // Include database connection
    require_once('db.php');

    // Prepare a delete statement
    $sql = "DELETE FROM notes WHERE note_id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ii", $param_note_id, $param_user_id);

        // Set parameters
        $param_note_id = $_POST['note_id'];
        $param_user_id = $_SESSION["user_id"];

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }

        // Close statement
        $stmt->close();
    } else {
        echo 'error';
    }

    // Close connection
    $conn->close();
} else {
    echo 'error';
}
?>
