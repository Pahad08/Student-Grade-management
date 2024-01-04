<?php

require_once dirname(__DIR__) . "\\controller\\controller.php";

if (
    isset($_POST['code']) && isset($_POST['subject']) && isset($_POST['description']) &&
    isset($_POST['code']) && isset($_POST['subject']) && isset($_POST['description'])
) {
    $controller = new controller("localhost", "root", "", "school");

    $add_subject = $controller->AddSubject($_POST['code'], $_POST['subject'], $_POST['description']);
    $controller->CloseDB();

    if ($add_subject == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Subject added!']);
    } else {
        echo json_encode(['error' => 'Subject already exist']);
    }
}
