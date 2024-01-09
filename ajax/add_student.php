<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $controller = new controller("localhost", "root", "", "school");

    $add_student = $controller->AddingStudent(
        $_POST['username'],
        $_POST['email'],
        $_POST['fname'],
        $_POST['lname'],
        (string)$_POST['number'],
        $_POST['section'],
        $_POST['g-level'],
        $_FILES['image'],
    );
    $controller->CloseDB();

    if ($add_student == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Student added!']);
    } elseif ($add_student == 'fail') {
        echo json_encode(['error' => 'Email Already Exist']);
    }
}
