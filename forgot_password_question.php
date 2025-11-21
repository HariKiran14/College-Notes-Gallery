<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once('db.php');

// Define variables and initialize with empty values
$email = $security_question = $security_answer = '';
$email_err = $security_answer_err = '';

// Get email from query parameter
if (isset($_GET['email'])) {
    $email = urldecode($_GET['email']);
}

// Fetch security question
if (!empty($email)) {
    $sql = "SELECT security_question FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $stmt->bind_result($security_question);
            if ($stmt->fetch()) {
                // Security question fetched successfully
            } else {
                $email_err = "No account found with that email.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
} else {
    header("location: forgot_password_email.php");
    exit;
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate security answer
    if (empty(trim($_POST["security_answer"]))) {
        $security_answer_err = "Please enter your security answer.";
    } else {
        $security_answer = trim($_POST["security_answer"]);
    }

    // Validate security answer
    if (empty($security_answer_err)) {
        $sql = "SELECT security_answer FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $stmt->bind_result($hashed_security_answer);
                if ($stmt->fetch()) {
                    if (password_verify($security_answer, $hashed_security_answer)) {
                        header("location: reset_password.php?email=" . urlencode($email));
                        exit;
                    } else {
                        $security_answer_err = "The security answer you entered was not valid.";
                    }
                } else {
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Security Question</title>
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

    h2 {
        font-size: 20px;
    }

    .form-group label, .form-control, .btn-primary {
        font-size: 12px; /* Adjust font size for smaller screens */
    }

    .btn-primary {
        padding: 8px;
    }
    body{
        background-color: #D4F1F4;
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
        <h2>Security Question</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?email=' . urlencode($email); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" readonly>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Security Question</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($security_question); ?>" readonly>
            </div>
            <div class="form-group <?php echo (!empty($security_answer_err)) ? 'has-error' : ''; ?>">
                <label>Security Answer</label>
                <input type="text" name="security_answer" class="form-control" value="<?php echo htmlspecialchars($security_answer); ?>">
                <span class="help-block"><?php echo $security_answer_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
