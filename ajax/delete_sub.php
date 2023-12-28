<?php

require_once dirname(__DIR__) . "\\controller\\controller.php";

if (isset($_GET['sub_id'])) {
    $controller = new controller("localhost", "root", "", "school");

    $delete_subject = $controller->DeleteSub($_GET['sub_id']);

    if ($delete_subject == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Subject Deleted!']);
    } else {
        echo json_encode(['error' => 'Theres an error, please try again']);
    }
}
