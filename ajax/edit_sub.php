<?php

require_once dirname(__DIR__) . "\\controller\\controller.php";

if (
    isset($_POST['code']) && isset($_POST['subject'])
    && isset($_POST['description']) && isset($_POST['sub_id'])
) {
    $controller = new controller("localhost", "root", "", "school");

    $add_subject = $controller->EditSub($_POST['code'], $_POST['subject'], $_POST['description'], $_POST['sub_id']);

    if ($add_subject == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Subject Edited!']);
    } else {
        echo json_encode(['error' => 'Theres an error, please try again']);
    }
} else {
    echo json_encode(['error' => 'no data']);
}
