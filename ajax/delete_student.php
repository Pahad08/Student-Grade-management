<?php
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

$controller = new controller("localhost", "root", "", "school");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $profile_pic = $controller->SelectProfilePic($_POST['student-id'], "students", "student_id");
    $profile = $profile_pic->fetch_assoc();
    $user_profile = $profile['profile_pic'];
    $profile_dir = $root  . "profile_pics" . DIRECTORY_SEPARATOR . basename($user_profile);

    if (file_exists($profile_dir)) {
        $delete_student = $controller->DeleteAccount($_POST['student-id'], "students", "student_id");
        $controller->CloseDB();
        $controller->CheckProfile($delete_student, $user_profile, "../view/view_students.php", "Student");
    } else {
        $_SESSION['img_err'] = 'Image doesnt exist!';
        header('location: ../view/view_students.php');
        exit();
    }
}
