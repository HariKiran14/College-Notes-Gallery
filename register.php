<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once('db.php');

// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = $role = $security_question = $security_answer = '';
$username_err = $email_err = $password_err = $confirm_password_err = $role_err = $profile_image_err = $security_question_err = $security_answer_err = '';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate inputs
    $username = !empty(trim($_POST["username"])) ? trim($_POST["username"]) : $username_err = "Please enter a username.";
    $email = !empty(trim($_POST["email"])) ? trim($_POST["email"]) : $email_err = "Please enter an email.";
    $password = !empty(trim($_POST["password"])) ? (strlen(trim($_POST["password"])) < 6 ? $password_err = "Password must have at least 6 characters." : trim($_POST["password"])) : $password_err = "Please enter a password.";
    $confirm_password = !empty(trim($_POST["confirm_password"])) ? trim($_POST["confirm_password"]) : $confirm_password_err = "Please confirm password.";
    $role = !empty(trim($_POST["role"])) ? trim($_POST["role"]) : $role_err = "Please select a role.";
    $security_question = !empty(trim($_POST["security_question"])) ? trim($_POST["security_question"]) : $security_question_err = "Please enter a security question.";
    $security_answer = !empty(trim($_POST["security_answer"])) ? password_hash(trim($_POST["security_answer"]), PASSWORD_DEFAULT) : $security_answer_err = "Please enter a security answer.";

    // Image upload handling
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['profile_image']['tmp_name'];
        $file_name = basename($_FILES['profile_image']['name']);
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $target_file = "uploads/" . uniqid('', true) . '.' . $file_extension;

        $check = getimagesize($file_tmp_name);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if ($check === false) {
            $profile_image_err = "File is not an image.";
        } elseif ($_FILES["profile_image"]["size"] > 6000000) {
            $profile_image_err = "File is too large (max 6MB).";
        } elseif (!in_array($file_extension, $allowed_types)) {
            $profile_image_err = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif (!move_uploaded_file($file_tmp_name, $target_file)) {
            $profile_image_err = "Error uploading file.";
        } else {
            $profile_image = $target_file;
        }
    } else {
        $profile_image_err = "Please upload a profile image.";
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err) && empty($profile_image_err) && empty($security_question_err) && empty($security_answer_err)) {

        $sql = "INSERT INTO users (username, email, password, role, profile_image, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password once
            $stmt->bind_param("sssssss", $username, $email, $hashed_password, $role, $profile_image, $security_question, $security_answer);
            if ($stmt->execute()) {
                header("location: index.php");
                exit;
            } else {
                $username_err = $stmt->errno == 1062 ? "Username already exists." : "Something went wrong. Please try again later.";
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
    <title>Register</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
    height: 100vh; 
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
h3 {
    margin-bottom: 15px;
    color: #657786;
    font-size: 16px;
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
.wrapper {
    position: absolute;
    top: 30px;
}

@media (max-width: 768px) {
    .wrapper {
        width: 300px;
        padding: 15px;
    }
    body{
        background-color: #D4F1F4;
    }

    h2 {
        font-size: 20px;
    }
    h3 {
        font-size: 14px;
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
    .wrapper {
        width: 250px;
        padding: 10px;
    }
    body{
        background-color: #D4F1F4;
    }

    h2 {
        font-size: 18px;
    }
    h3 {
        font-size: 12px;
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
    <h4>Join the College Notes Gallery Community</h4>
     <h2 >Register</h2>
        <h3>Please fill your details to create an account</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo htmlspecialchars($confirm_password); ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="" disabled selected>Select your role</option>
                    <option value="Student" <?php echo ($role == 'Student') ? 'selected' : ''; ?>>Student</option>
                    <option value="Faculty" <?php echo ($role == 'Faculty') ? 'selected' : ''; ?>>Faculty</option>
                </select>
                <span class="help-block"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($security_question_err)) ? 'has-error' : ''; ?>">
                <label>Security Question</label>
                <input type="text" name="security_question" class="form-control" value="<?php echo htmlspecialchars($security_question); ?>">
                <span class="help-block"><?php echo $security_question_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($security_answer_err)) ? 'has-error' : ''; ?>">
                <label>Security Answer</label>
                <input type="text" name="security_answer" class="form-control" value="<?php echo htmlspecialchars($security_answer); ?>">
                <span class="help-block"><?php echo $security_answer_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($profile_image_err)) ? 'has-error' : ''; ?>">
                <label>Profile Image</label>
                <input type="file" name="profile_image" class="form-control">
                <span class="help-block"><?php echo $profile_image_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Register">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>

