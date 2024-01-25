<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" || isset($_POST['add'])) {
    $controller = new controller("localhost", "root", "", "school");
    $profile_pic = $controller->SelectProfilePic($_POST['student_id'], "students", "student_id");
    $profile = $profile_pic->fetch_assoc();
    $user_profile = $profile['profile_pic'];

    $edit_student = $controller->Editstudent(
        $_POST['username'],
        $_POST['email'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['gender'],
        $_POST['number'],
        $_POST['section'],
        $_POST['g-level'],
        $_FILES['image'],
        $_POST['student_id']
    );
    $controller->CloseDB();
    if ($edit_student == 'success') {
        if ($_FILES['image']['size'] !== 0 && $user_profile !== "..\profile_pics\user.png") {
            unlink($user_profile);
        }
        echo json_encode(['status' => 'edited', 'message' => 'Student Edited!']);
    } elseif ($edit_student == 'duplicate') {
        echo json_encode(['status' => 'duplicate', 'message' => 'Email already exist!']);
    } else {
        echo json_encode(['error' => 'Error, please try again']);
    }
}
