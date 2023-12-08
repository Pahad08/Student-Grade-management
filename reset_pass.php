<?php

if (isset($_POST['password']) && isset($_POST['token'])) {
    require 'conn.php';
    $sql1 = "SELECT email from token where token = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("s", $_POST['token']);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row = $result1->fetch_assoc();
    $email = $row['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql2 = "UPDATE accounts SET `password` = ? where email = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ss", $password, $email);

    $sql3 = "DELETE from token where token = ?;";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("s", $_POST['token']);

    if ($stmt3->execute() && $stmt2->execute()) {
        $conn->close();
        echo json_encode(['status' => 'OK', 'message' => "Password reset successfully"]);
    } else {
        $conn->close();
        echo json_encode(['message' => "Theres an error, please try again"]);
    }
}
