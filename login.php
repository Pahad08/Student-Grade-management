<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();

function SelectUser($conn, $usertype, $username)
{
    $stmt = $conn->prepare("SELECT * from $usertype WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function redirect($conn, $location, $user_id)
{
    $_SESSION['id'] = $user_id;
    $conn->close();
    header("location:" . $location);
    exit();
}

if (isset($_POST['login']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    require 'conn.php';

    switch ($_POST['usertype']) {
        case '1':

            $user = SelectUser($conn, "admins", $_POST['username']);
            $user_info = $user->fetch_assoc();
            $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
            $username = ($user->num_rows > 0) ? $user_info['username'] : "";
            $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

            if ($user->num_rows > 0 && password_verify($_POST['password'], $hashed_pass)) {
                redirect($conn, "admin.php", $user_id);
            } else {
                $mess_failed = "Incorrect Username or Password";
                $conn->close();
            }

            break;

        case '2':

            $user = SelectUser($conn, "students", $_POST['username']);
            $user_info = $user->fetch_assoc();
            $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
            $username = ($user->num_rows > 0) ? $user_info['username'] : "";
            $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

            if ($user->num_rows > 0 && password_verify($_POST['password'], $hashed_pass)) {
                redirect($conn, "student.php", $user_id);
            } else {
                $mess_failed = "Incorrect Username or Password";
                $conn->close();
            }

            break;
    }
}

if (isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    $token = bin2hex(random_bytes(32));
    $resetLink = "localhost/studentmanagement/reset_password.php?token=" . $token;

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mastahpahad@gmail.com';
        $mail->Password   = 'gaocjcwwezyylzvk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('mastahpahad@gmail.com');
        $mail->addAddress($_POST['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Password';
        $mail->Body =  "<a href='$resetLink'>Click here to reset your password</a>";

        $mail->send();

        echo "<script>alert('Check Your Email!')</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <title>Login</title>
</head>

<body>

    <div class="login-container">

        <div class="form-header">
            <img src="images/logo.png" alt="school">
            <?php if (!isset($_GET['r'])) { ?>
                <h1>School Management System</h1>
            <?php } else { ?>
                <p id="text-forgot">You forgot your password? Here you can <br> easily retrieve a new password.</p>
            <?php } ?>
        </div>


        <?php if (!isset($_GET['r'])) { ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="login-form" method="POST">

                <div class="input-body">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter Your Username">
                    <?php if (isset($mess_failed)) { ?>
                        <p><?php echo $mess_failed; ?></p>
                    <?php } ?>
                </div>

                <div class="input-body">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter Your Password">
                    <?php if (isset($mess_failed)) { ?>
                        <p><?php echo $mess_failed; ?></p>
                    <?php unset($mess_failed);
                    } ?>
                </div>

                <div class="input-body">
                    <label for="usertype">Usertype</label>
                    <select name="usertype" id="usertype">
                        <option value="1">Admin</option>
                        <option value="2">Student</option>
                    </select>
                </div>

                <button name="login" id="login-btn" value="login">Login</button>
            </form>
        <?php } else { ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="login-form" method="POST">

                <div class="input-body">
                    <label for="Email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter Your Email" required>
                </div>

                <button name="submit" id="send-btn" value="submit">Submit</button>
            </form>

        <?php } ?>

        <hr>

        <?php if (!isset($_GET['r'])) { ?>
            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?r=forgotpass" ?>" id="forgot-pass">Forgot
                Password</a>
        <?php } else { ?>
            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="forgot-pass">Login Here</a>
        <?php } ?>

    </div>

</body>

</html>