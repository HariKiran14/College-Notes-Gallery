<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once('db.php');

// Define variables and initialize with empty values
$email = '';
$email_err = '';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check email exists
    if (empty($email_err)) {
        $sql = "SELECT user_id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    header("location: forgot_password_question.php?email=" . urlencode($email));
                    exit;
                } else {
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "SQL Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Prepare Error: " . $conn->error;
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
    <title>Forgot Password</title>
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
    height: 100vh; /* Ensure full viewport height */
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
    outline: none;
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

@media (max-width: 768px) {
    .wrapper {
        width: 80%;
        padding: 15px;
    }
    h2 {
        font-size: 20px;
    }
    .form-control {
        font-size: 12px;
    }
    .btn-primary {
        padding: 8px 12px;
        font-size: 12px;
    }
    .wrapper a {
        font-size: 12px;
    }
}

@media (max-width: 576px) {
    body{
        background-color: #D4F1F4;
    }
    .wrapper {
        width: 70%;
        padding: 10px;
    }
    h2 {
        font-size: 18px;
    }
    .form-control {
        font-size: 12px;
    }
    .btn-primary {
        padding: 6px 10px;
        font-size: 12px;
    }
    .wrapper a {
        font-size: 12px;
    }
}

    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Forgot Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
