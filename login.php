<?php
// Start session
session_start();

// Include database connection
require_once('db.php');

// Define variables and initialize with empty values
$username = $password = '';
$username_err = $password_err = '';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username/email and password are empty
    if (empty(trim($_POST["username_email"]))) {
        $username_err = "Please enter username or email.";
    } else {
        $username = trim($_POST["username_email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT user_id, username, email, password, role FROM users WHERE username = ? OR email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username/email exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($user_id, $username, $email, $hashed_password, $role);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;

                            // Redirect user to index.html
                            header("location: index.php");
                            exit();
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username/email doesn't exist
                    $username_err = "No account found with that username/email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Reset some default styles */
        body, html {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url('http://localhost/college_notes_gallery/images/OIP.png'); /* Update to public URL */
            background-size: cover;
            background-attachment: fixed; /* Prevents background from scrolling */
            background-position: center;
            height: 100vh; /* Ensure full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        /* Login container styling */
        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-wrapper h2 {
            margin-bottom: 10px;
            font-size: 28px;
            color: #333;
        }

        .login-wrapper h3 {
            margin-bottom: 20px;
            font-size: 16px;
            color: #666;
        }

        .form-group {
            position: relative;
            margin-bottom: 15px;
        }

        .form-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #999;
        }

        .form-control {
            width: calc(100% - 40px);
            padding: 10px 10px 10px 30px;
            margin: 5px 0 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .help-block {
            color: red;
            font-size: 12px;
        }

        .remember-me {
            margin: 10px 0;
            font-size: 14px;
        }

        .remember-me input {
            margin-right: 5px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive styling */
        @media (max-width: 1200px) {
            body{
        background-color: #D4F1F4;
    }
            .login-wrapper h2 {
                font-size: 24px;
            }

            .login-wrapper h3 {
                font-size: 14px;
            }
        }

        @media (max-width: 992px) {
            body{
        background-color: #D4F1F4;
    }
            .login-container {
                padding: 20px;
            }

            .login-wrapper h2 {
                font-size: 22px;
            }
            .login-wrapper h3 {
                font-size: 12px;
            }

            .login-wrapper h3 {
                font-size: 12px;
            }
        }

        @media (max-width: 768px) {
            body{
        background-color: #D4F1F4;
    }
            .login-container {
                padding: 15px;
            }
            .login-container h1{
                font-size: 25px;
            }
            .login-wrapper h2 {
                font-size: 20px;
            }

            .login-wrapper h3 {
                font-size: 10px;
            }

            .form-control {
                width: calc(100% - 30px);
            }

            .btn {
                width: 100%;
            }
            .login-wrapper{
                padding: 15px;
            }
            .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);
            max-width: 250px;
            width: 100%;
            text-align: center;
        }

     
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="navbar">
            <h1>Welcome to Malnad College Notes Gallery</h1>
        </div>
        <div class="login-wrapper">
            <h2>Login</h2>
            <h3>Please enter your details to log in</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <i class="fa fa-user"></i>
                    <input type="text" name="username_email" class="form-control" placeholder="Username or Email" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" value="Sign In">
                </div>
                <div class="remember-me">
                    <input type="checkbox" name="remember"> Remember Me
                </div>
                <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                <p><a href="forgot_password.php">Forgot Password?</a></p>
            </form>
        </div>
    </div>
</body>
</html>
