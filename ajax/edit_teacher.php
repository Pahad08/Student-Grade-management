<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $controller = new controller("localhost", "root", "", "school");
    $profile_pic = $controller->SelectProfilePic($_POST['teacher-id'], "teachers", "teacher_id");
    $profile = $profile_pic->fetch_assoc();
    $user_profile = $profile['profile_pic'];

    $edit_teacher = $controller->Editteacher(
        $_POST['fname'],
        $_POST['lname'],
        $_POST['gender'],
        $_FILES['image'],
        $_POST['username'],
        $_POST['email'],
        $_POST['teacher-id']
    );
    $controller->CloseDB();
    if ($edit_teacher == 'success') {
        if ($_FILES['image']['size'] !== 0 && $user_profile !== "..\profile_pics\user.png") {
            unlink($user_profile);
        }
        echo json_encode(['status' => 'edited', 'message' => 'Teacher Edited!']);
    } elseif ($edit_teacher == 'duplicate') {
        echo json_encode(['status' => 'duplicate', 'message' => 'Email already exist!']);
    } elseif ($edit_teacher == 'fail') {
        echo json_encode(['status' => 'fail', 'message' => 'Theres an failure in editing teacher']);
    } else {
        echo json_encode(['error' => 'Error, please try again']);
    }
}
