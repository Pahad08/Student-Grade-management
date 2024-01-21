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

if (isset($_GET['acc_id'])) {
    $get_student = $controller->GetTeacher($_GET['acc_id']);
    $teacher = $get_student->fetch_assoc();
    $get_acc = $controller->GetAccount($_GET['acc_id']);
    $account = $get_acc->fetch_assoc();
} else {
    $id_column = "teacher_id";
    $table = "teachers";
    $page_num = $_GET['page_num'];
    $num_perpage = 5;
    $next_page =  $_GET['page_num'] + 1;
    $prev_page =  $_GET['page_num'] - 1;
    $min = $page_num;
    $max = $page_num + 1;
    $offset = ($page_num * $num_perpage) - $num_perpage;
    $get_teacher = $controller->SelectTeachers($num_perpage, $offset);
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
        <title>Teachers</title>
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

                <ul class="dropdown-text active">
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
                    <?php if (!isset($_GET['sub_id'])) { ?>
                    <h1>Teachers</h1>
                    <?php } else { ?>
                    <h1>Edit Teacher</h1>
                    <?php } ?>
                </div>

                <hr>

                <div class="main-body">

                    <?php if (!isset($_GET['acc_id'])) { ?>
                    <div class="table-container">

                        <div class="table-header">
                            <a href="add_teachers.php">Add New</a>
                        </div>

                        <div class="search-bar">
                            <label for="search">Search: </label>
                            <input type="text" id="search-teacher">
                        </div>

                        <div class="table-body">

                            <table id="table">

                                <tr class="row">
                                    <th class="table-head">Name</th>
                                    <th class="table-head">Gender</th>
                                    <th class="table-head">Profile Pic</th>
                                    <th class="table-head">Action</th>
                                </tr>

                                <?php while ($teacher = $get_teacher->fetch_assoc()) { ?>
                                <tr class="row">

                                    <td class="data">
                                        <?php echo $teacher['f_name'] . " " . $teacher['l_name']  ?></td>
                                    <td class="data"><?php echo ($teacher['gender'] == "F") ? "Female" : "Male" ?></td>
                                    <td class="data data-img"><img
                                            src="../profile_pics/<?php echo $teacher['profile_pic']  ?>"
                                            id="profile-pic">
                                    </td>
                                    <td class="data action">
                                        <button class="btn-delete" data-id="<?php echo $teacher['account_id'] ?>">
                                            <img src="../images/delete.png" alt="delete" class="delete-sub">
                                        </button>
                                        <button class="btn-edit">
                                            <a
                                                href=<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?acc_id=" . $teacher['account_id']) ?>><img
                                                    src="../images/edit.png" alt="Edit" class="edit-sub"></a>
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
                                        <form action='../ajax/delete_teacher.php' method="post" id="teacher-delete">
                                            <input type="text" id="acc-id" name="acc-id" hidden>
                                            <button id="delete-teacher" name="delete_teacher" value="delete_teacher"
                                                class="delete">Delete</button>
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
                                <li id="previous"><a
                                        <?php echo ($page_num == 1) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$prev_page}") ?>>Previous</a>
                                </li>

                                <li class="active-page">
                                    <a
                                        href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?page_num={$min}"  ?>"><?php echo $min ?></a>
                                </li>

                                <?php if ($page_num != $total_pages) { ?>
                                <li class="next-page">
                                    <a
                                        href="<?php echo (Checkpage($total_pages, $page_num)) ? "" : htmlspecialchars($_SERVER['PHP_SELF']) . "?page_num={$max}" ?>"><?php echo (Checkpage($total_pages, $page_num)) ? "" : $max ?></a>
                                </li>
                                <?php } ?>

                                <li id="next">
                                    <a
                                        <?php echo ($page_num == $total_pages) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$next_page}"); ?>>Next</a>
                                </li>
                            </ul>

                        </div>

                    </div>

                    <?php } else { ?>

                    <div class="form-body">

                        <form id="edit-teacherform" enctype="multipart/form-data" action="../ajax/edit_teacher.php">

                            <input type="text" name="acc_id" id="acc_id" value="<?php echo $teacher['account_id'] ?>"
                                hidden>

                            <div class="input-container teacher-form">
                                <div class="input-body" id="fname-body">
                                    <label for="fname">First Name</label>
                                    <input type="text" name="fname" id="fname" value="<?php echo $teacher['f_name'] ?>">
                                </div>

                                <div class="input-body" id="lname-body">
                                    <label for="lname">Last Name</label>
                                    <input type="text" name="lname" id="lname" value="<?php echo $teacher['l_name'] ?>">
                                </div>

                                <div class="input-body" id="gender-body">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender">
                                        <option value="<?php echo $teacher['gender'] ?>">
                                            <?php echo ($teacher['gender'] == "F") ? "Female" : "Male" ?></option>

                                        <option value="<?php echo ($teacher['gender'] == "F") ? "M" : "F" ?>">
                                            <?php echo ($teacher['gender'] == "F") ? "Male" : "Female" ?></option>

                                    </select>
                                </div>


                                <div class="input-body" id="image-body">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image" accept="image/*">
                                </div>

                                <div class="input-body" id="username-body">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username"
                                        value="<?php echo $account['username'] ?>">
                                </div>

                                <div class="input-body" id="emailbody">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" value="<?php echo $account['email'] ?>">
                                </div>

                            </div>

                            <div class="editbtn-body">
                                <button id="edit-btn" name="add" value="add">Edit</button>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                                    id="cancel-edit">Cancel</a>
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