<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if (isset($_POST['code']) && isset($_POST['subject']) && isset($_POST['description'])) {
    $controller = new controller("localhost", "root", "", "school");

    $add_subject = $controller->AddSubject($_POST['code'], $_POST['subject'], $_POST['description']);
    $controller->CloseDB();

    if ($add_subject == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Subject added!']);
    } else {
        echo json_encode(['error' => 'Subject already exist']);
    }
}
