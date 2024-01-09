<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if (isset($_GET['sub'])) {
    $controller = new controller("localhost", "root", "", "school");
    $search_subject = $controller->SearchSubjects($_GET['sub']);
    $controller->CloseDB();

    echo "<tr class='subject-row'>
    <th class='subject-header'>Code</th>
    <th class='subject-header'>Subject</th>
    <th class='subject-header'>Description</th>
    <th class='subject-header'>Action</th>
</tr>";

    while ($result = $search_subject->fetch_assoc()) {
        echo "<tr class='subject-row'>";
        echo "<td class='subject-data'>" . $result['code'] . "</td>";
        echo "<td class='subject-data'>" . $result['subject'] . "</td>";
        echo "<td class='subject-data'>" . $result['description'] . "</td>";
        echo "<td class='subject-data action'>";
        echo "<button class='btn-delete' data-id='" . $result['subject_id'] . "'>";
        echo "<img src='../images/delete.png' alt='delete' class='delete-sub'>";
        echo "</button>";
        echo "<button class='btn-edit'>";
        echo "<a href=" . htmlspecialchars("../view/view_subject.php" . "?sub_id=" .
            $result['subject_id']) . "><img src='../images/edit.png' alt='Edit' class='edit-sub'></a>";
        echo "</button>";
        echo "</td>";
        echo "</tr>";
    }
}
