<?php
// Start session
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once('db.php');

// Check if note_id parameter is set in URL
if (!isset($_GET['note_id']) || !is_numeric($_GET['note_id'])) {
    header('Location: notes_listing.php');
    exit;
}

// Define variables and initialize with empty values
$title = $description = '';
$title_err = $description_err = '';

// Process form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate title
    if (empty(trim($_POST['title']))) {
        $title_err = 'Please enter a title.';
    } else {
        $title = trim($_POST['title']);
    }

    // Validate description
    if (empty(trim($_POST['description']))) {
        $description_err = 'Please enter a description.';
    } else {
        $description = trim($_POST['description']);
    }

    // Check input errors before updating the note
    if (empty($title_err) && empty($description_err)) {
        // Prepare an update statement
        $sql = 'UPDATE notes SET title = ?, description = ? WHERE note_id = ? AND user_id = ?';

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('ssii', $param_title, $param_description, $param_note_id, $param_user_id);

            // Set parameters
            $param_title = $title;
            $param_description = $description;
            $param_note_id = $_GET['note_id'];
            $param_user_id = $_SESSION['user_id']; // Current user's ID

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to notes listing page after successful update
                header('location: notes_listing.php');
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
} else {
    // Retrieve note details from database
    $sql = 'SELECT title, description FROM notes WHERE note_id = ? AND user_id = ?';

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param('ii', $param_note_id, $param_user_id);

        // Set parameters
        $param_note_id = $_GET['note_id'];
        $param_user_id = $_SESSION['user_id']; // Current user's ID

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if note exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($title, $description);
                if ($stmt->fetch()) {
                    // Note details retrieved successfully, display edit form
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Edit Note</title>
                    <link rel="stylesheet" href="styles.css">
                </head>
                <body>
                    <div class="wrapper">
                        <h2>Edit Note</h2>
                        <p>Please fill in the details to edit your note.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?note_id='.$_GET['note_id']; ?>" method="post">
                            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
                                <span class="help-block"><?php echo $title_err; ?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                                <label>Description</label>
                                <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                                <span class="help-block"><?php echo $description_err; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Submit">
                                <a href="notes_listing.php" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </body>
                </html>
                <?php
                }
            } else {
                // Note not found, redirect to notes listing page
                header('Location: notes_listing.php');
                exit;
            }
        } else {
            echo 'Oops! Something went wrong. Please try again later.';
        }

        // Close statement
        $stmt->close();
    }
}
?>

