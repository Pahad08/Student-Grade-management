<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" || isset($_POST['add'])) {
    $controller = new controller("localhost", "root", "", "school");
    $password = (isset($_POST['password'])) ? $_POST['password'] : "";
    $profile_pic = $controller->SelectProfilePic($_POST['acc_id']);
    $profile = $profile_pic->fetch_assoc();
    $user_profile = $profile['profile_pic'];

    $edit_student = $controller->Editstudent(
        $_POST['username'],
        $_POST['email'],
        $password,
        $_POST['fname'],
        $_POST['lname'],
        $_POST['number'],
        $_POST['section'],
        $_POST['g-level'],
        $_FILES['image'],
        $_POST['acc_id']
    );
    $controller->CloseDB();
    if ($edit_student == 'success' && file_exists($user_profile)) {
        unlink($user_profile);
        echo json_encode(['status' => 'OK', 'message' => 'Student Edited!']);
    } elseif ($edit_student == 'duplicate') {
        echo json_encode(['status' => 'duplicate', 'message' => 'Email already exist!']);
    } elseif ($edit_student == 'fail') {
        echo json_encode(['status' => 'fail', 'message' => 'Theres an failure in editing student']);
    } else {
        echo json_encode(['error' => 'Error, please try again']);
    }
}
