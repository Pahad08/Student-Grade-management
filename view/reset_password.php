<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

$controller = new controller("localhost", "root", "", "school");
$token_data = $controller->SelectToken();
$expiration_date = $token_data['expiration_date'];
$token = $token_data['token'];
$current_time = time();

if (strtotime($token_data['expiration_date']) < $current_time || empty($token)) {
    $controller->CloseDB();
    header("location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Reset Password</title>
</head>

<body>

    <div class="loader-body">
        <div id="loader"></div>
    </div>

    <div class="login-container">

        <div class="form-header">
            <img src="../images/logo.png" alt="school">
            <h2>Reset Password</h2>
        </div>

        <form class="form" id="reset-form">

            <div class="input-body">
                <label for="password">New Password</label>
                <input type="password" name="password" required placeholder="Enter new password">
            </div>

            <input type="text" name="token" value="<?php echo $token; ?>" hidden>

            <button id="reset-pass" value="password" name="password">Reset Password</button>
        </form>
    </div>

</body>

<script src="../js/login.js"></script>

</html>