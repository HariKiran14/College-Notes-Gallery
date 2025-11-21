<?php
// Start session
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

// Include database connection file
require_once "db.php";

// Get user information from the database
$user_id = $_SESSION["id"];
$sql = "SELECT username, email FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email);
    $stmt->fetch();
    $stmt->close();
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST["email"];
    $sql = "UPDATE users SET email = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $new_email, $user_id);
        if ($stmt->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error updating profile.";
        }
        $stmt->close();
    }
    header("location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <div style="text-align:center;margin-top: -35px;">
        <img style="width: 300px;" src="http://localhost/college_notes_gallery/images/logo.png" alt="College Notes Gallery">
    </div>
    <header>
        <h1 style="font-size:50px;text-align:center;margin-top: -65px;">User Profile</h1> 
        <hr style="margin-top: -26px;height:2px;border-width:0;color:rgb(7, 4, 4);background-color:rgb(18, 5, 5)">
        
        <div style="text-align:center;margin-top: 26px;">
            <a style="padding-left: 20px;"></a>
            <a style="padding-right: 20px;" href="index.html">Home</a>
            <a style="padding-right: 20px;" href="upload_note.php">Upload Notes</a>
            <a style="padding-right: 20px;" href="uploaded_notes.php">Uploaded Notes</a>
            <a style="padding-right: 20px;" href="about_us.html">About</a>
            <a style="padding-right: 20px;" href="logout.php">Logout</a>
        </div>
    </header>
    
    <div class="container" style="text-align:center;">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <form action="profile.php" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <br><br>
            <input type="submit" value="Update Profile">
        </form>
    </div>

    <footer>
        <p style="margin-top:20px;text-align:center">&copy; 2024 College Notes Gallery. All rights reserved to team12.</p>
    </footer>
</body>
</html>
