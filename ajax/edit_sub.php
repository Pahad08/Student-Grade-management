<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if (
    isset($_POST['code']) && isset($_POST['subject'])
    && isset($_POST['description']) && isset($_POST['sub_id'])
) {
    $controller = new controller("localhost", "root", "", "school");

    $edit_subject = $controller->EditSub($_POST['code'], $_POST['subject'], $_POST['description'], $_POST['sub_id']);
    $controller->CloseDB();
    if ($edit_subject == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Subject Edited!']);
    } else {
        echo json_encode(['error' => 'Subject already exist']);
    }
}
