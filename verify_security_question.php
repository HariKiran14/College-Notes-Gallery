<?php
// verify_security_question.php
session_start();
require_once('db.php');

$email = $security_question = $security_answer = '';
$email_err = $security_question_err = $security_answer_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $security_question = trim($_POST["security_question"]);
    $security_answer = trim($_POST["security_answer"]);

    if (empty($email) || empty($security_question) || empty($security_answer)) {
        if (empty($email)) $email_err = "Email is required.";
        if (empty($security_question)) $security_question_err = "Security question is required.";
        if (empty($security_answer)) $security_answer_err = "Answer is required.";
    } else {
        $sql = "SELECT security_question, security_answer FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($stored_question, $stored_answer);
                    if ($stmt->fetch()) {
                        if ($security_question === $stored_question && password_verify($security_answer, $stored_answer)) {
                            header("location: reset_password.php?email=$email");
                        } else {
                            $security_answer_err = "The answer or question you entered was not valid.";
                        }
                    }
                } else {
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong.";
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
    <title>Verify Security Question</title>
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
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Verify Security Question</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" readonly>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($security_answer_err)) ? 'has-error' : ''; ?>">
                <label>Security Answer</label>
                <input type="text" name="security_answer" class="form-control" value="<?php echo htmlspecialchars($security_answer); ?>">
                <span class="help-block"><?php echo $security_answer_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn-primary" value="Verify Answer">
            </div>
            <p>Remembered your answer? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>

