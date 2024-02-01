<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();
    $controller = new controller("localhost", "root", "", "school");
    $id = (isset($_POST['teacher_id'])) ? $_POST['teacher_id'] : $_POST['student_id'];
    $usertype = (isset($_POST['teacher_id'])) ? "teachers" : "students";

    $edit_pass = $controller->ChangePassword($id, $usertype, $_POST['curr-pass'], $_POST['new-pass']);
    $controller->CloseDB();
    if ($edit_pass == 'success') {
        $_SESSION['message'] = "Successfully Changed!";
        if (isset($_POST['teacher_id'])) {
            header('location: ../view/teacher.php');
        } else {
            header('location: ../view/student.php');
        }
        exit();
    } elseif ($edit_pass == 'wrong') {
        $_SESSION['message'] = "Wrong Password!";
        if (isset($_POST['teacher_id'])) {
            header('location: ../view/teacher.php');
        } else {
            header('location: ../view/student.php');
        }
        exit();
    } else {
        $_SESSION['message'] = "Error in editing password!";
        if (isset($_POST['teacher_id'])) {
            header('location: ../view/teacher.php');
        } else {
            header('location: ../view/student.php');
        }
        exit();
    }
}