<?php

session_start();

if (!isset($_SESSION['admins_id'])) {
    header("location: login.php");
} else {
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';
}

function Checkpage($total_pages, $page_num)
{
    return $total_pages == 1 || $page_num == $total_pages;
}

if (!isset($_GET['page_num']) || $_GET['page_num'] <= 0) {
    $_GET['page_num'] = 1;
}

$controller = new controller("localhost", "root", "", "school");
$subjects = $controller->SelectSubjects();
$student = $controller->GetStudent($_GET['student']);
$get_student = $student->fetch_assoc();
$grade = substr($get_student['grade_level'], 6);
$section = $get_student['section'];
$student_id = $get_student['student_id'];

$student_grades = $controller->GetGrades($student_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Grade 7</title>
</head>

<body>

    <div class="loader-body">
        <div id="loader"></div>
    </div>

    <div class="sidebar">

        <div class="logo-header">
            <img src="../images/logo.png" alt="logo">
        </div>

        <hr>

        <div class="user-info">
            <p>Admin/Teacher</p>
        </div>

        <hr>

        <nav id="nav-bar" class="nav-bar">

            <ul class="dashboard">
                <li> <a href="admin.php">Dashboard</a></li>
            </ul>

            <ul class="dropdown-text">
                <p>Subjects</p>
                <img src="../images/arrow.png" alt="arrow" class="arrow">
            </ul>

            <ul class="dropdown">
                <ul>
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="add_subject.php">Add
                            Subjects</a>
                    </li>
                </ul>

                <ul>
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="view_subject.php">View
                            Subjects</a>
                    </li>
                </ul>
            </ul>

            <ul class="dropdown-text">
                <p>Students</p>
                <img src="../images/arrow.png" alt="arrow" class="arrow">
            </ul>

            <ul class="dropdown">
                <ul>
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="add_students.php">Add
                            Students</a>
                    </li>
                </ul>

                <ul>
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="view_students.php">View
                            Students</a>
                    </li>
                </ul>
            </ul>

            <ul class="sections">
                <li> <a href="sections.php" class="active">Sections</a></li>
            </ul>

            <ul class="logout">
                <li><a href="logout.php">Logout</a></a></li>
            </ul>

        </nav>

    </div>

    <div class="body">

        <div class="header">
            <div class="menu-icon">
                <img src="../images/menu.png" alt="menu" id="menu-icon">
            </div>
        </div>

        <div class="info">

            <div class="text">
                <h1>Section A</h1>
            </div>

            <hr>

            <div class="main-body">

                <div class="table-container">

                    <div class="table-body" style="padding: 4px;">

                        <div class="student-info">
                            <h2>Student</h2>
                            <p><?php echo $get_student['f_name'] . " " . $get_student['l_name'] ?></p>
                        </div>

                        <hr>

                        <div class="edit-grade">
                            <form action="../ajax/add_grade.php" id="add-grade" method="post">

                                <input type="text" value="<?php echo $get_student['student_id'] ?>" name="student_id" hidden>

                                <input type="text" value="<?php echo $get_student['account_id'] ?>" name="account_id" hidden>

                                <div class="input" id="subject-container">
                                    <label for="select-sub" class="input-label">Subject</label>
                                    <select name="subject" id="select-sub">
                                        <option value="">Select Subject</option>
                                        <?php while ($row = $subjects->fetch_assoc()) { ?>
                                            <option value="<?php echo $row['subject_id'] ?>">
                                                <?php echo $row['subject'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="input" id="grade-container">
                                    <label for="grade" class="input-label">Grade</label>
                                    <input type="number" max="100" id="grade" name="grade">
                                </div>

                                <div class="button-grade">
                                    <button id="grade-add">Add</button>
                                    <a href="<?php echo "grade_lvl/grade_{$grade}.php?sec={$section}" ?>" id="cancel">Cancel</a>
                                </div>

                            </form>
                        </div>

                        <table id="table" style="margin-top: 10px;">

                            <tr class="row">
                                <th class="table-head">Subject</th>
                                <th class="table-head">Grade</th>
                                <th class="table-head"></th>
                            </tr>

                            <?php while ($get_grades = $student_grades->fetch_assoc()) { ?>
                                <tr class="row">

                                    <td class="data"><?php echo $get_grades['subject'] ?></td>
                                    <td class="data"><?php echo $get_grades['grade'] ?></td>
                                    <td class="data action">
                                        <form action="../ajax/delete_grade.php" method="post">
                                            <input type="text" name="subject_id" value="<?php echo $get_grades['grade_id'] ?>" hidden>
                                            <input type="text" value="<?php echo $get_student['account_id'] ?>" name="account_id" hidden>
                                            <button class="btn-delete" data-id="<?php echo $students['account_id'] ?>">
                                                <img src="../images/delete.png" alt="delete" class="delete-sub">
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            <?php } ?>
                        </table>


                    </div>

                </div>

            </div>


        </div>

    </div>

</body>

<script src="../js/admin.js"></script>
<script src="../js/nav.js"></script>

</html>