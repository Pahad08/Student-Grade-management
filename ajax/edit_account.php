<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();
    $controller = new controller("localhost", "root", "", "school");
    $id = (isset($_POST['teacher_id'])) ? $_POST['teacher_id'] : $_POST['student_id'];
    $usertype = (isset($_POST['teacher_id'])) ? "teachers" : "students";

    $edit_account = $controller->ChangeAccount($id, $usertype, $_POST['username'], $_POST['email']);
    $controller->CloseDB();
    if ($edit_account == 'success') {
        $_SESSION['message'] = "Successfully Changed!";
        if (isset($_POST['teacher_id'])) {
            header('location: ../view/teacher.php');
        } else {
            header('location: ../view/student.php');
        }
        exit();
    } elseif ($edit_account == 'duplicate') {
        $_SESSION['message'] = "Email already taken!";
        if (isset($_POST['teacher_id'])) {
            header('location: ../view/teacher.php');
        } else {
            header('location: ../view/student.php');
        }

        exit();
    } else {
        $_SESSION['message'] = "Error in editing personal information!";
        if (isset($_POST['teacher_id'])) {
            header('location: ../view/teacher.php');
        } else {
            header('location: ../view/student.php');
        }

        exit();
    }
}