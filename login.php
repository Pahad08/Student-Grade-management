<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("location: admin.php");
} elseif (isset($_SESSION['student_id'])) {
    header("location: student.php");
}

function SelectUser($conn, $usertype, $username)
{
    $stmt = $conn->prepare("SELECT * from accounts
    join $usertype on accounts.account_id = $usertype.account_id
    where username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function redirect($conn, $location, $user_id, $usertype)
{
    $_SESSION[$usertype . "_id"] = $user_id;
    $conn->close();
    header("location:" . $location);
    exit();
}

function CleanData($conn, $data)
{
    $data = stripslashes($data);
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

if (isset($_POST['login']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    require 'conn.php';

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $mess_failed = "Username or password cannot be empty.";
        $conn->close();
    } else {
        switch ($_POST['usertype']) {
            case '1':

                $user = SelectUser($conn, "admins", CleanData($conn, $_POST['username']));
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
                $username = ($user->num_rows > 0) ? $user_info['username'] : "";
                $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

                if ($user->num_rows > 0 && password_verify(CleanData($conn, $_POST['password']), $hashed_pass)) {
                    redirect($conn, "admin.php", $user_id, "admin");
                } else {
                    $mess_failed = "Incorrect Username or Password";
                    $conn->close();
                }

                break;

            case '2':

                $user = SelectUser($conn, "students", CleanData($conn, $_POST['username']));
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
                $username = ($user->num_rows > 0) ? $user_info['username'] : "";
                $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

                if ($user->num_rows > 0 && password_verify(CleanData($conn, $_POST['password']), $hashed_pass)) {
                    redirect($conn, "student.php", $user_id, "student");
                } else {
                    $mess_failed = "Incorrect Username or Password";
                    $conn->close();
                }

                break;
        }
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

        <div class="loader-body">
            <div id="loader"></div>
        </div>

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
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="login-form" method="POST"
                class="form">

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
                    <img src="images/close-eye.png" id="eye">
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

    <script src="js/login.js"></script>

</html>