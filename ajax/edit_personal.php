<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();
    $controller = new controller("localhost", "root", "", "school");
    $id = (isset($_POST['teacher_id'])) ? $_POST['teacher_id'] : $_POST['student_id'];
    $usertype = (isset($_POST['teacher_id'])) ? "teachers" : "students";
    if (isset($_POST['teacher_id'])) {
        $edit_personal = $controller->ChangePersonal($id, $usertype, $_POST['fname'], $_POST['lname'], $_POST['gender']);
    } else {
        $edit_personal = $controller->ChangePersonal($id, $usertype, $_POST['fname'], $_POST['lname'], $_POST['gender'], $_POST['contact_num']);
    }

    $controller->CloseDB();
    if ($edit_personal == 'success') {

        $_SESSION['message'] = "Successfully Changed!";
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