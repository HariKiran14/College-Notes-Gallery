<?php
// Include database connection
require_once('db.php');

// Define variables for search query and category
$search_query = $category = '';
$search_err = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get search query and category from form
    $search_query = trim($_POST['search_query']);
    $category = $_POST['category'];

    // Validate search query (optional)
    if (empty($search_query)) {
        $search_err = 'Please enter a search query.';
    }

    // Construct SQL query based on search criteria
    $sql = 'SELECT note_id, title, description FROM notes WHERE';

    // Add search conditions
    if (!empty($search_query)) {
        $sql .= " (title LIKE '%$search_query%' OR description LIKE '%$search_query%')";
    }

    // Add category condition
    if (!empty($category)) {
        $sql .= " AND category = '$category'";
    }

    // Execute the query
    $result = $conn->query($sql);

    // Display search results
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo '<div class="note">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<a href="view_note.php?id=' . $row['note_id'] . '">View Note</a>';
            echo '</div>';
        }
    } else {
        echo 'No notes found matching your search criteria.';
    }

    // Close connection
    $conn->close();
}
?>
