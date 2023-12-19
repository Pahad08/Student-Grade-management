<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("location: admin.php");
} elseif (isset($_SESSION['student_id'])) {
    header("location: student.php");
}

require_once dirname(__DIR__) . "\\controller\\controller.php";

if (isset($_POST['login']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $mess_failed = "Username or password cannot be empty.";
    } else {
        $controller = new controller("localhost", "root", "", "school");
        if ($_POST['usertype'] == 1) {
            $mess_failed = $controller->login("admins", $_POST['username'], $_POST['password']);
        } else {
            $mess_failed = $controller->login("students", $_POST['username'], $_POST['password']);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <title>Login</title>
</head>

<body>

    <div class="loader-body">
        <div id="loader"></div>
    </div>

    <div class="login-container">

        <div class="form-header">
            <img src="../images/logo.png" alt="school">
            <?php if (!isset($_GET['r'])) { ?>
                <h1>School Management System</h1>
            <?php } else { ?>
                <p id="text-forgot">You forgot your password? Here you can <br> easily retrieve a new password.</p>
            <?php } ?>
        </div>


        <?php if (!isset($_GET['r'])) { ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="login-form" method="POST" class="form">

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
                    <img src="../images/close-eye.png" id="eye">
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
            <form id="email-form" method="POST" class="form">

                <div class="input-body">
                    <label for="Email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter Your Email" required>
                </div>

                <button name="submit" id="send-btn" value="submit">Submit</button>
            </form>

        <?php } ?>

        <hr>

        <?php if (!isset($_GET['r'])) { ?>

            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?r=forgotpass")  ?>" id="forgot-pass">Forgot
                Password</a>
        <?php } else { ?>
            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="forgot-pass">Login Here</a>
        <?php } ?>

    </div>

</body>

<script src="../js/login.js"></script>

</html>