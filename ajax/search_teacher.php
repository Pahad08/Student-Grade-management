<?php

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";

if (isset($_GET['teacher'])) {
    $controller = new controller("localhost", "root", "", "school");
    $search_teacher = $controller->SearchTeacher($_GET['teacher']);
    $controller->CloseDB();

    echo "<tr class='row'>
    <th class='table-head'>Name</th>
    <th class='table-head'>Gender</th>
    <th class='table-head'>Profile Pic</th>
    <th class='table-head'>Action</th>
</tr>";

    while ($teacher = $search_teacher->fetch_assoc()) {
        echo "<tr class='row'>";
        echo "<td class='data'>" . $teacher['f_name'] . " " . $teacher['l_name'] . "</td>";
        echo "<td class='data'>";
        echo ($teacher['gender'] == "F") ? "Female" : "Male" . "</td>";
        echo '<td class="data data-img"><img src="../profile_pics/' . $teacher['profile_pic'] . '"' . "id='profile-pic'>
                                        </td>";
        echo '<td class="data action">';
        echo '<button class="btn-delete" data-id="' . $teacher['account_id'] . '"' . "><img src='../images/delete.png' alt='delete' class='delete-sub'></button>";
        echo '<button class="btn-edit"> <a href=' . htmlspecialchars("../view/view_teachers.php" . "?acc_id=" . $teacher['account_id']) .
            '><img src="../images/edit.png" alt="Edit" class="edit-sub"></a></button>';

        echo "</td>";
        echo "</tr>";
    }
}
