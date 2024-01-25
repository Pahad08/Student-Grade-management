<?php

session_start();

if (!isset($_SESSION['admins_id'])) {
    header("location: login.php");
} else {
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';
}

if (!isset($_GET['page_num']) || $_GET['page_num'] <= 0) {
    $_GET['page_num'] = 1;
}

$controller = new controller("localhost", "root", "", "school");

function Checkpage($total_pages, $page_num)
{
    return $total_pages == 1 || $page_num == $total_pages;
}

if (isset($_SESSION['deleted_student'])) {

    $deleted_student = $_SESSION['deleted_student'];
    echo "<script>alert('$deleted_student')</script>";
    unset($_SESSION['deleted_student']);
} elseif (isset($_SESSION['delete_err'])) {

    $error_del = $_SESSION['delete_err'];
    echo "<script>alert('$error_del')</script>";
    unset($_SESSION['delete_err']);
} elseif (isset($_SESSION['img_err'])) {

    $error_img = $_SESSION['img_err'];
    echo "<script>alert('$error_img')</script>";
    unset($_SESSION['img_err']);
}

if (isset($_GET['student_id'])) {
    $get_student = $controller->GetStudent($_GET['student_id']);
    $student = $get_student->fetch_assoc();
    // $get_acc = $controller->GetAccount($_GET['student_id']);
    // $account = $get_acc->fetch_assoc();
} else {
    $id_column = "student_id";
    $table = "students";
    $page_num = $_GET['page_num'];
    $num_perpage = 5;
    $next_page =  $_GET['page_num'] + 1;
    $prev_page =  $_GET['page_num'] - 1;
    $min = $page_num;
    $max = $page_num + 1;
    $offset = ($page_num * $num_perpage) - $num_perpage;
    $get_students = $controller->SelectStudents($num_perpage, $offset);
    $total_pages = $controller->GetTotalpages($id_column, $num_perpage, $table);
}

$controller->CloseDB();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Students</title>
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
            <p>Administrator</p>
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

            <ul class="dropdown-text active">
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

            <ul class="dropdown-text">
                <p>Teachers</p>
                <img src="../images/arrow.png" alt="arrow" class="arrow">
            </ul>

            <ul class="dropdown">
                <ul>
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="add_teachers.php">Add
                            Teachers</a>
                    </li>
                </ul>

                <ul>
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="view_teachers.php">View
                            Teachers</a>
                    </li>
                </ul>
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
                <?php if (!isset($_GET['student_id'])) { ?>
                    <h1>Students</h1>
                <?php } else { ?>
                    <h1>Edit Student</h1>
                <?php } ?>
            </div>

            <hr>

            <div class="main-body">

                <?php if (!isset($_GET['student_id'])) { ?>
                    <div class="table-container">

                        <div class="table-header">
                            <a href="add_students.php">Add New</a>
                        </div>

                        <div class="search-bar">
                            <label for="search">Search: </label>
                            <input type="text" id="search-student">
                        </div>

                        <div class="table-body">

                            <table id="table">

                                <tr class="row">
                                    <th class="table-head">Name</th>
                                    <th class="table-head">Gender</th>
                                    <th class="table-head">Contact Number</th>
                                    <th class="table-head">Section</th>
                                    <th class="table-head">Grade Level</th>
                                    <th class="table-head">Profile Pic</th>
                                    <th class="table-head">Action</th>
                                </tr>

                                <?php while ($students = $get_students->fetch_assoc()) { ?>
                                    <tr class="row">

                                        <td class="data">
                                            <?php echo $students['f_name'] . " " . $students['l_name']  ?></td>
                                        <td class="data"><?php echo ($students['gender'] == "F") ? "Female" : "Male" ?></td>
                                        <td class="data"><?php echo $students['contact_number']  ?></td>
                                        <td class="data"><?php echo $students['section']  ?></td>
                                        <td class="data"><?php echo $students['grade_level']  ?></td>
                                        <td class="data"><img src="../profile_pics/<?php echo $students['profile_pic']  ?>" id="profile-pic">
                                        </td>
                                        <td class="data action">
                                            <button class="btn-delete" data-id="<?php echo $students['student_id'] ?>">
                                                <img src="../images/delete.png" alt="delete" class="delete-sub">
                                            </button>
                                            <button class="btn-edit">
                                                <a href=<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?student_id=" . $students['student_id']) ?>><img src="../images/edit.png" alt="Edit" class="edit-sub"></a>
                                            </button>
                                        </td>

                                    </tr>
                                <?php } ?>

                            </table>

                            <div class="alert-body">

                                <div class="alert-container">
                                    <div class="alert-header">
                                        <p>Delete</p>
                                    </div>

                                    <div class="alert-text">
                                        Are you sure you want to delete this subject?
                                    </div>

                                    <div class="alert-footer">
                                        <button id="cancel-delete">Cancel</button>
                                        <form action='../ajax/delete_student.php' method="post" id="sub-delete">
                                            <input type="text" id="student-id" name="student-id" hidden>
                                            <button id="delete-student" name="delete_student" value="delete_student" class="delete">Delete</button>
                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="pagination">

                            <div class="pagination-info">
                                <p>Showing <?php echo $page_num . " to " . $total_pages ?></p>
                            </div>

                            <ul class="pagination-body">
                                <li id="previous"><a <?php echo ($page_num == 1) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$prev_page}") ?>>Previous</a>
                                </li>

                                <li class="active-page">
                                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?page_num={$min}"  ?>"><?php echo $min ?></a>
                                </li>

                                <?php if ($page_num != $total_pages) { ?>
                                    <li class="next-page">
                                        <a href="<?php echo (Checkpage($total_pages, $page_num)) ? "" : htmlspecialchars($_SERVER['PHP_SELF']) . "?page_num={$max}" ?>"><?php echo (Checkpage($total_pages, $page_num)) ? "" : $max ?></a>
                                    </li>
                                <?php } ?>

                                <li id="next">
                                    <a <?php echo ($page_num == $total_pages) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$next_page}"); ?>>Next</a>
                                </li>
                            </ul>

                        </div>

                    </div>

                <?php } else { ?>

                    <div class="form-body">

                        <form id="edit-studentform" enctype="multipart/form-data" action="../ajax/edit_student.php">

                            <input type="text" name="student_id" id="student_id" value="<?php echo $student['student_id'] ?>" hidden>

                            <div class="input-container student-form">
                                <div class="input-body" id="fname-body">
                                    <label for="fname">First Name</label>
                                    <input type="text" name="fname" id="fname" value="<?php echo $student['f_name'] ?>">
                                </div>

                                <div class="input-body" id="lname-body">
                                    <label for="lname">Last Name</label>
                                    <input type="text" name="lname" id="lname" value="<?php echo $student['l_name'] ?>">
                                </div>

                                <div class="input-body" id="gender-body">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender">
                                        <option value="<?php echo $student['gender'] ?>">
                                            <?php echo ($student['gender'] == "F") ? "Female" : "Male" ?></option>

                                        <option value="<?php echo ($student['gender'] == "F") ? "M" : "F" ?>">
                                            <?php echo ($student['gender'] == "F") ? "Male" : "Female" ?></option>

                                    </select>
                                </div>

                                <div class="input-body" id="number-body">
                                    <label for="number">Contact Number</label>
                                    <input type="number" name="number" id="number" inputmode="numeric" value="<?php echo $student['contact_number'] ?>">
                                </div>

                                <div class="input-body" id="section-body">
                                    <label for="section">Section</label>
                                    <input type="text" name="section" id="section" maxlength="1" value="<?php echo $student['section'] ?>">
                                </div>

                                <div class="input-body" id="glevel-body">
                                    <label for="g-level">Grade Level</label>
                                    <input type="text" name="g-level" id="g-level" value="<?php echo $student['grade_level'] ?>">
                                </div>

                                <div class="input-body" id="image-body">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image" accept="image/*">
                                </div>

                                <div class="input-body" id="username-body">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" value="<?php echo $student['username'] ?>">
                                </div>

                                <div class="input-body" id="email-body">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" value="<?php echo $student['email'] ?>">
                                </div>

                            </div>

                            <div class="editbtn-body">
                                <button id="edit-btn" name="add" value="add">Edit</button>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="cancel-edit">Cancel</a>
                                <input type="reset" id="reset-btn" value="Reset">
                            </div>

                        </form>

                    </div>

                <?php } ?>

            </div>


        </div>

    </div>

</body>

<script src="../js/admin.js"></script>
<script src="../js/nav.js"></script>

</html>