<?php
// Start session
session_start();

// Check if the user is logged in and is faculty
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'Faculty') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Include database connection
require_once('db.php');

// Check if note_id is set
if(isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];

    // Prepare a select statement
    $sql = "SELECT title, subject, description, uploaded_at FROM notes WHERE note_id = ? AND user_id = ?";
    if($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ii", $note_id, $_SESSION['user_id']);

        // Attempt to execute the prepared statement
        if($stmt->execute()) {
            // Store result
            $result = $stmt->get_result();

            // Check if note exists
            if($result->num_rows == 1) {
                // Fetch the note details
                $note = $result->fetch_assoc();
                echo json_encode(['success' => true, 'title' => $note['title'], 'subject' => $note['subject'], 'description' => $note['description'], 'uploaded_at' => $note['uploaded_at']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Note not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error executing query']);
        }

        // Close statement
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

// Close connection
$conn->close();
?>
