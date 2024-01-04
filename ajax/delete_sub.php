<?php

require_once dirname(__DIR__) . "\\controller\\controller.php";

$controller = new controller("localhost", "root", "", "school");

if (isset($_POST['delete_sub']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $delete_subject = $controller->DeleteSub($_POST['sub_id']);
    $controller->CloseDB();

    if ($delete_subject == 'success') {
        $_SESSION['deleted_sub'] = 'Subject Deleted!';
        header('location: ../view/view_subject.php');
        exit();
    } else {
        $_SESSION['delete_err'] = 'Theres an error, please try again!';
        header('location: ../view/view_subject.php');
        exit();
    }
}
