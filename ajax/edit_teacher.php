<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $controller = new controller("localhost", "root", "", "school");
    $password = (isset($_POST['password'])) ? $_POST['password'] : "";
    $profile_pic = $controller->SelectProfilePic($_POST['acc_id'], "teachers");
    $profile = $profile_pic->fetch_assoc();
    $user_profile = $profile['profile_pic'];

    $edit_teacher = $controller->Editteacher(
        $_POST['username'],
        $_POST['email'],
        $password,
        $_POST['fname'],
        $_POST['lname'],
        $_POST['gender'],
        $_FILES['image'],
        $_POST['acc_id']
    );
    $controller->CloseDB();
    if ($edit_teacher == 'success' && file_exists($user_profile)) {
        if ($_FILES['image']['size'] !== 0) {
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
