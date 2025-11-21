<?php
function getUserDetails($userId) {
    global $conn;
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        die("Error executing statement: " . $stmt->error);
    }

    return $result->fetch_assoc();
}
?>

