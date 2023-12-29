<?php

require_once dirname(__DIR__) . "\\controller\\controller.php";

$message = array();

if (empty($_POST['code']) && empty($_POST['subject']) && empty($_POST['description'])) {
    echo json_encode(['status' => 'empty_inputs', 'message' => 'Fill out all of the inputs']) . " ";
    exit();
}

if (empty($_POST['code'])) {
    $code_array = array(['status' => 'empty_code', 'message' => 'Code cannot be empty']);
    array_push($message, $code_array);
}

if (empty($_POST['subject'])) {
    $subject_array = array(['status' => 'empty_subject', 'message' => 'Subject cannot be empty']);
    array_push($message, $subject_array);
}

if (empty($_POST['description'])) {
    $description_array = array(['status' => 'empty_description', 'message' => 'Description cannot be empty']);
    array_push($message, $description_array);
}

if (!empty($message)) {
    echo json_encode($message);
}

if (!empty($_POST['code']) && !empty($_POST['subject']) && !empty($_POST['description'])) {
    $controller = new controller("localhost", "root", "", "school");

    $add_subject = $controller->AddSubject($_POST['code'], $_POST['subject'], $_POST['description']);

    if ($add_subject == 'success') {
        echo json_encode(['status' => 'OK', 'message' => 'Subject added!']);
    } else {
        echo json_encode(['error' => 'Theres an error, please try again']);
    }
}