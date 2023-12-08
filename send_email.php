<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['email'])) {

    require 'conn.php';

    $query = $conn->prepare("SELECT email from accounts where email = ?;");
    $query->bind_param("s",  $_POST['email']);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_array();

    if ($result->num_rows == 0) {
        echo json_encode(['error' => 'Theres an error, please try again']);
    } else {

        $email = $row['email'];
        $token = bin2hex(random_bytes(32));
        $expiration_date = date('Y-m-d H:i:s', time() + (5 * 60));
        $sql = "INSERT into token(token, expiration_date, email) values(?,?,?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expiration_date, $email);
        $stmt->execute();
        $conn->close();
        $base_url = ($_SERVER['HTTP_HOST'] == "localhost") ? "localhost/studentmanagement" : "domainwebsite.com";
        $resetLink =  $base_url . "/reset_password.php?token=" . urlencode($token);
        $conn->close();

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

            if ($mail->send()) {
                echo json_encode(['status' => 'OK', 'message' => 'Check your email!']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Theres an error, please try again']);
        }
    }
}
