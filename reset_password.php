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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // $sql1 = "DELETE from token where token = ?;";
    // $stmt1 = $conn->prepare($sql1);
    // $stmt1->bind_param("s", $token);
    // $stmt1->execute();

    // $sql2 = "SELECT email from token where token = ?";
    // $stmt2 = $conn->prepare($sql2);
    // $stmt2->bind_param("s", $token);
    // $stmt2->execute();

    // $sql2 = "UPDATE ";
    // $stmt2 = $conn->prepare($sql2);
    // $stmt2->bind_param("s", $token);
    // $stmt2->execute();

    echo "<script>alert('Your password has been reset successfully!')
    window.location.href = 'login.php'</script>";
}

if (strtotime($data['expiration_date']) < $current_time && empty($token)) {
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

        <h2>Reset Password</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?token=" . $token); ?>">
            <label for="password">New Password:</label>
            <input type="password" name="password" required><br>

            <button id="reset-pass" value="password" name="password">Reset Password</button>
        </form>

    </body>

</html>