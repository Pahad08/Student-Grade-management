<?php

session_start();

if (!isset($_SESSION['admins_id'])) {
    header("location: login.php");
} else {
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . "controller" . DIRECTORY_SEPARATOR . "controller.php";
}

if (!isset($_GET['page_num']) || $_GET['page_num'] <= 0) {
    $_GET['page_num'] = 1;
}

$page_num = $_GET['page_num'];
$num_perpage = 5;
$next_page =  $_GET['page_num'] + 1;
$prev_page =  $_GET['page_num'] - 1;
$min = $page_num;
$max = $page_num + 1;
$offset = ($page_num * $num_perpage) - $num_perpage;

$controller = new controller("localhost", "root", "", "school");
$get_subjects = $controller->GetSubjects($num_perpage, $offset);

function Checkpage($total_pages, $page_num)
{
    return $total_pages == 1 || $page_num == $total_pages;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <title>Add Teacher</title>
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
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="">Add
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
                <h1>Add Teacher</h1>
            </div>

            <hr>

            <div class="main-body">

                <div class="form-body">

                    <form id="teacher-form" method="post" enctype="multipart/form-data">

                        <div class="input-container teacher-form">
                            <div class="input-body" id="fname-body">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" id="fname">
                            </div>

                            <div class="input-body" id="lname-body">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname">
                            </div>

                            <div class="input-body" id="gender-body">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="F">Female</option>
                                    <option value="M">Male</option>
                                </select>
                            </div>

                            <div class="input-body" id="image-body">
                                <label for="image">Image</label>
                                <input type="file" name="image" id="image" accept="image/*">
                            </div>

                            <div class="input-body" id="username-body">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username">
                            </div>

                            <div class="input-body" id="emailbody">
                                <label for=" email">Email</label>
                                <input type="email" name="email" id="email">
                            </div>

                        </div>

                        <div class="addbtn-body">
                            <button id="add-btn" name="add" value="add">Add</button>
                            <input type="reset" id="reset-btn" value="Reset">
                        </div>

                    </form>

                </div>

            </div>


        </div>

    </div>

</body>

<script src="../js/index.js"></script>
<script src="../js/nav.js"></script>

</html>