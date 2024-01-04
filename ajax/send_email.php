<?php

require_once dirname(__DIR__) . "\\controller\\controller.php";

if (isset($_POST['email'])) {
    $controller = new controller("localhost", "root", "", "school");

    $send_email = $controller->SelectEmail($_POST['email']);
    $controller->CloseDB();

    if ($send_email == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Check your email!']);
    } elseif ($send_email == 'fail') {
        echo json_encode(['status' => 'fail', 'error' => 'Theres an error, please try again']);
    } else {
        echo json_encode(['empty' => 'No Email Found']);
    }
}
