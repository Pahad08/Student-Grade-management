<?php

$token = (isset($_GET["token"])) ? $_GET["token"] : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    echo "<script>alert('Your password has been reset successfully!')
    window.location.href = 'login.php'</script>";
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

    <h2>Reset Password</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="password">New Password:</label>
        <input type="password" name="password" required><br>

        <button id="reset-pass" value="password" name="password">Reset Password</button>
    </form>

</body>

</html>