<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once('db.php');

// Define variables and initialize with empty values
$email = $new_password = $confirm_password = '';
$new_password_err = $confirm_password_err = '';

// Get email from query parameter
if (isset($_GET['email'])) {
    $email = urldecode($_GET['email']);
} else {
    header("location: forgot_password_email.php");
    exit;
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter a new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your new password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Update password in the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $hashed_password, $email);
            if ($stmt->execute()) {
                header("location: login.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
      body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-image: url('http://localhost/college_notes_gallery/images/OIP.png');
    background-size: cover;
    background-attachment: fixed; /* Prevents background from scrolling */
    background-position: center;
}

.wrapper {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 350px;
}

h2 {
    margin-bottom: 15px;
    color: #333;
    font-size: 24px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
}

.form-control:focus {
    border-color: #007bff;
}

.help-block {
    color: red;
    font-size: 12px;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    color: #fff;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 14px;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.has-error .form-control {
    border-color: red;
}

.wrapper a {
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
}

.wrapper a:hover {
    text-decoration: underline;
}

/* Media Queries */
@media (max-width: 767px) {
    .wrapper {
        width: 70%; /* Adjust width for smaller screens */
        padding: 10px;
    }
    body{
        background-color: #D4F1F4;
    }

    h2 {
        font-size: 20px;
    }

    .form-group label, .form-control, .btn-primary {
        font-size: 12px; /* Adjust font size for smaller screens */
    }

    .btn-primary {
        padding: 8px;
    }
}

@media (max-width: 479px) {
    .wrapper {
        width: 70%; /* Further adjust width for very small screens */
        padding: 10px;
    }
    body{
        background-color: #D4F1F4;
    }

    h2 {
        font-size: 18px;
    }

    .form-group label, .form-control, .btn-primary {
        font-size: 10px; /* Smaller font size for very small screens */
    }

    .btn-primary {
        padding: 6px;
    }
}

    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?email=' . urlencode($email); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo htmlspecialchars($new_password); ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo htmlspecialchars($confirm_password); ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>

