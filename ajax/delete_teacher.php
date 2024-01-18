<?php
session_start();
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

$controller = new controller("localhost", "root", "", "school");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $profile_pic = $controller->SelectProfilePic($_POST['acc-id'], "teachers");
    $profile = $profile_pic->fetch_assoc();
    $user_profile = $profile['profile_pic'];

    if (file_exists($user_profile)) {
        $delete_student = $controller->DeleteAccount($_POST['acc-id']);
        $controller->CloseDB();
        $controller->CheckProfile($delete_student, $user_profile, "../view/view_teachers.php", "Teacher");
    } else {
        $_SESSION['img_err'] = 'Image doesnt exist!';
        header('location: ../view/view_students.php');
        exit();
    }
}
