<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";
    $controller = new controller("localhost", "root", "", "school");
    $check_result = $controller->ResetPass($_POST['token'], $_POST['password']);
    $controller->CloseDB();

    if ($check_result == 'success') {
        echo json_encode(['status' => 'OK', 'message' => "Password reset successfully"]);
    } else {
        echo json_encode(['message' => "Theres an error, please try again"]);
    }
}