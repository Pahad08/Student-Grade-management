<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();
    $controller = new controller("localhost", "root", "", "school");
    $id = (isset($_POST['teacher_id'])) ? $_POST['teacher_id'] : $_POST['student_id'];
    $table = (isset($_POST['teacher_id'])) ? "teachers" : "students";
    $column = (isset($_POST['teacher_id'])) ? "teacher_id" : "student_id";
    $profile_pic = $controller->SelectProfilePic($id, $table, $column);
    $profile = $profile_pic->fetch_assoc();
    $user_profile = $profile['profile_pic'];

    $edit_profile = $controller->ChangeProfile($id, $table, $_FILES['image']);
    $controller->CloseDB();
    if ($edit_profile == 'success') {
        unlink($user_profile);
        $_SESSION['message'] = "Successfully Changed!";
        header('location: ../view/teacher.php');
        exit();
    } else {
        $_SESSION['message'] = "Error in editing profile!";
        header('location: ../view/teacher.php');
        exit();
    }
}
