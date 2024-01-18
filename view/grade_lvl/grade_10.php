<?php

session_start();

if (!isset($_SESSION['admins_id'])) {
    header("location: login.php");
} else {
    $root = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
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
$section = $_GET['sec'];
$id_column = "student_id";
$table = "students";
$glvl = "Grade 10";
$page_num = $_GET['page_num'];
$num_perpage = 5;
$next_page =  $_GET['page_num'] + 1;
$prev_page =  $_GET['page_num'] - 1;
$min = $page_num;
$max = $page_num + 1;
$offset = ($page_num * $num_perpage) - $num_perpage;
$get_students = $controller->GetStudents($num_perpage, $offset, $section, $glvl);
$total_pages = $controller->GetTotalpages($id_column, $num_perpage, $table, $section, $glvl);
$controller->CloseDB();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/admin.css">
        <link rel="shortcut icon" href="../../images/logo.png" type="image/x-icon">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <title>Grade 10</title>
    </head>

    <body>

        <div class="loader-body">
            <div id="loader"></div>
        </div>

        <div class="sidebar">

            <div class="logo-header">
                <img src="../../images/logo.png" alt="logo">
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
                    <img src="../../images/arrow.png" alt="arrow" class="arrow">
                </ul>

                <ul class="dropdown">
                    <ul>
                        <li> <img src="../../images/arrow.png" alt="arrow" class="arrow"><a
                                href="../add_subject.php">Add
                                Subjects</a>
                        </li>
                    </ul>

                    <ul>
                        <li> <img src="../../images/arrow.png" alt="arrow" class="arrow"><a
                                href="../view_subject.php">View
                                Subjects</a>
                        </li>
                    </ul>
                </ul>

                <ul class="dropdown-text">
                    <p>Students</p>
                    <img src="../../images/arrow.png" alt="arrow" class="arrow">
                </ul>

                <ul class="dropdown">
                    <ul>
                        <li> <img src="../../images/arrow.png" alt="arrow" class="arrow"><a
                                href="../add_students.php">Add
                                Students</a>
                        </li>
                    </ul>

                    <ul>
                        <li> <img src="../../images/arrow.png" alt="arrow" class="arrow"><a
                                href="../view_students.php">View
                                Students</a>
                        </li>
                    </ul>
                </ul>

                <ul class="dropdown-text">
                    <p>Teachers</p>
                    <img src="../../images/arrow.png" alt="arrow" class="arrow">
                </ul>

                <ul class="dropdown">
                    <ul>
                        <li> <img src="../../images/arrow.png" alt="arrow" class="arrow"><a
                                href="../add_teachers.php">Add
                                Teachers</a>
                        </li>
                    </ul>

                    <ul>
                        <li> <img src="../../images/arrow.png" alt="arrow" class="arrow"><a
                                href="../view_teachers.php">View
                                Teachers</a>
                        </li>
                    </ul>
                </ul>

                <ul class="sections">
                    <li> <a href="../sections.php" class="active">Sections</a></li>
                </ul>

                <ul class="logout">
                    <li><a href="../logout.php">Logout</a></a></li>
                </ul>

            </nav>

        </div>

        <div class="body">

            <div class="header">
                <div class="menu-icon">
                    <img src="../../images/menu.png" alt="menu" id="menu-icon">
                </div>
            </div>

            <div class="info">

                <div class="text">
                    <h1><?php echo "Section " . $_GET['sec'] ?></h1>
                </div>

                <hr>

                <div class="main-body">

                    <div class="table-container">

                        <div class="table-body" style="padding: 4px;">

                            <table id="table">

                                <tr class="row">
                                    <th class="table-head">First Name</th>
                                    <th class="table-head">Last Name</th>
                                    <th class="table-head">Grades</th>
                                </tr>

                                <?php while ($students = $get_students->fetch_assoc()) { ?>
                                <tr class="row">

                                    <td class="data"><?php echo $students['f_name'] ?></td>
                                    <td class="data"><?php echo $students['l_name']  ?></td>
                                    <td class="data action">
                                        <a class="view"
                                            href="../edit_grade.php?student=<?php echo $students['account_id'] ?>">
                                            <img src="../../images/open-eye.png" alt="">
                                        </a>

                                    </td>

                                </tr>
                                <?php } ?>

                            </table>


                            <div class="pagination">

                                <div class="pagination-info">
                                    <p>Showing <?php echo $page_num . " to " . $total_pages ?></p>
                                </div>

                                <ul class="pagination-body">
                                    <li id="previous"><a
                                            <?php echo ($page_num == 1) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$prev_page}&sec=A") ?>>Previous</a>
                                    </li>

                                    <li class="active-page">
                                        <a
                                            href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?page_num={$min}&sec=A"  ?>"><?php echo $min ?></a>
                                    </li>

                                    <?php if ($page_num != $total_pages) { ?>
                                    <li class="next-page">
                                        <a
                                            href="<?php echo (Checkpage($total_pages, $page_num)) ? "" : htmlspecialchars($_SERVER['PHP_SELF']) . "?page_num={$max}&sec=A" ?>"><?php echo (Checkpage($total_pages, $page_num)) ? "" : $max ?></a>
                                    </li>
                                    <?php } ?>

                                    <li id="next">
                                        <a
                                            <?php echo ($page_num == $total_pages) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$next_page}&sec=A"); ?>>Next</a>
                                    </li>
                                </ul>

                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </body>

    <script src="../../js/admin.js"></script>
    <script src="../../js/nav.js"></script>

</html>