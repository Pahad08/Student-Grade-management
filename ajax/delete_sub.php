<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

$controller = new controller("localhost", "root", "", "school");

if (isset($_POST['delete_sub']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $delete_subject = $controller->DeleteSub((int)$_POST['sub_id']);
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
