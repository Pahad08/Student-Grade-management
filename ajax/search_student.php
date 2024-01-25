<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if (isset($_GET['student'])) {
    $controller = new controller("localhost", "root", "", "school");
    $search_students = $controller->SearchStudents($_GET['student']);
    $controller->CloseDB();

    echo "<tr class='row'>
    <th class='table-head'>Name</th>
    <th class='table-head'>Gender</th>
    <th class='table-head'>Contact Number</th>
    <th class='table-head'>Section</th>
    <th class='table-head'>Grade Level</th>
    <th class='table-head'>Profile Pic</th>
    <th class='table-head'>Action</th>
</tr>";

    while ($students = $search_students->fetch_assoc()) {
        echo "<tr class='row'>";
        echo "<td class='data'>" . $students['f_name'] . " " . $students['l_name'] . "</td>";
        echo "<td class='data'>";
        echo ($students['gender'] == "F") ? "Female" : "Male" . "</td>";
        echo "<td class='data'>" . $students['contact_number'] . "</td>";
        echo "<td class='data'>" . $students['section'] . "</td>";
        echo "<td class='data'>" . $students['grade_level'] . "</td>";
        echo '<td class="data"><img src="../profile_pics/' . $students['profile_pic'] . '"' . "id='profile-pic'>
                                        </td>";
        echo '<td class="data action">';
        echo '<button class="btn-delete" data-id="' . $students['student_id'] . '"' . "><img src='../images/delete.png' alt='delete' class='delete-sub'></button>";
        echo '<button class="btn-edit"> <a href=' . htmlspecialchars("../view/view_students.php?" . "student_id=" . $students['student_id']) .
            '><img src="../images/edit.png" alt="Edit" class="edit-sub"></a></button>';

        echo "</td>";
        echo "</tr>";
    }
}
