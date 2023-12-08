<?php
if (isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    require 'conn.php';

    $query = $conn->prepare("SELECT email from accounts where email = ?;");
    $query->bind_param("s",  $_POST['email']);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array();

    if ($result->num_rows == 0) {
        $mess_failed = "Email not found";
        $conn->close();
        echo "<script>alert('Email not found!')
        window.location.href = 'login.php?r=forgotpass'</script>";
    } else {

        $email = $row['email'];
        $token = bin2hex(random_bytes(32));
        $expiration_date = date('Y-m-d H:i:s', time() + (5 * 60));
        $sql = "INSERT into token(token, expiration_date, email) values(?,?,?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expiration_date, $email);
        $stmt->execute();
        $conn->close();
        $base_url = ($_SERVER['HTTP_HOST'] == "localhost") ? "localhost/studentmanagement" : "resetpassword.com";
        $resetLink =  urlencode($base_url . "/reset_password.php?token=" . $token);

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
}
