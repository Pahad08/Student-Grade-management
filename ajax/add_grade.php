<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $controller = new controller("localhost", "root", "", "school");


    $controller->CloseDB();

    if ($add_student == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Student added!']);
    } elseif ($add_student == 'fail') {
        echo json_encode(['error' => 'Email Already Exist']);
    }
}
