<?php
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $controller = new controller("localhost", "root", "", "school");

    $addgrade = $controller->AddGrade($_POST['student_id'], $_POST['grade'], $_POST['subject']);

    $controller->CloseDB();

    if ($addgrade == 'success') {
        header("location: ../view/edit_grade.php?student={$_POST['student_id']}");
        exit();
    } elseif ($addgrade == 'fail') {
        header("location: ../view/edit_grade.php?student={$_POST['student_id']}");
        exit();
    }
}
