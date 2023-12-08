<?php
require 'conn.php';

$sql = "SELECT * from token where token = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_GET["token"]);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$current_time = time();
$token = $data['token'];

if (strtotime($data['expiration_date']) < $current_time || empty($token)) {
    $conn->close();
    header("location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <title>Reset Password</title>
</head>

<body>

    <div class="loader-body">
        <div id="loader"></div>
    </div>

    <div class="login-container">

        <div class="form-header">
            <img src="images/logo.png" alt="school">
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

<script src="js/login.js"></script>

</html>