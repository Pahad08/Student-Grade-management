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

$controller = new controller("localhost", "root", "", "school");


function Checkpage($total_pages, $page_num)
{
    return $total_pages == 1 || $page_num == $total_pages;
}

if (isset($_SESSION['deleted_sub'])) {
    $deleted_sub = $_SESSION['deleted_sub'];
    echo "<script>alert('$deleted_sub')</script>";
    unset($_SESSION['deleted_sub']);
} elseif (isset($_SESSION['delete_err'])) {
    $error_del = $_SESSION['delete_err'];
    echo "<script>alert('$error_del')</script>";
    unset($_SESSION['delete_err']);
}

if (isset($_GET['sub_id'])) {
    $get_subject = $controller->GetSubject($_GET['sub_id']);
    $result = $get_subject->fetch_assoc();
} else {
    $id_column = "subject_id";
    $table = "subjects";
    $page_num = $_GET['page_num'];
    $num_perpage = 5;
    $next_page =  $_GET['page_num'] + 1;
    $prev_page =  $_GET['page_num'] - 1;
    $min = $page_num;
    $max = $page_num + 1;
    $offset = ($page_num * $num_perpage) - $num_perpage;
    $get_subjects = $controller->GetSubjects($num_perpage, $offset);
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
        <title>Subjects</title>
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

                <ul class="dropdown-text active">
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
                    <?php if (!isset($_GET['sub_id'])) { ?>
                    <h1>Subjects</h1>
                    <?php } else { ?>
                    <h1>Edit Subject</h1>
                    <?php } ?>
                </div>

                <hr>

                <div class="main-body">

                    <?php if (!isset($_GET['sub_id'])) { ?>
                    <div class="table-container">

                        <div class="table-header">
                            <a href="add_subject.php">Add New</a>
                        </div>

                        <div class="search-bar">
                            <label for="search">Search: </label>
                            <input type="text" id="search-sub">
                        </div>

                        <div class="table-body">

                            <table id="table">

                                <tr class="row">
                                    <th class="table-head">Code</th>
                                    <th class="table-head">Subject</th>
                                    <th class="table-head">Description</th>
                                    <th class="table-head">Action</th>
                                </tr>

                                <?php while ($subjects = $get_subjects->fetch_assoc()) { ?>
                                <tr class="row">

                                    <td class="data"><?php echo $subjects['code']  ?></td>
                                    <td class="data"><?php echo $subjects['subject']  ?></td>
                                    <td class="data description"><?php echo $subjects['description']  ?></td>
                                    <td class="data action">
                                        <button class="btn-delete" data-id="<?php echo $subjects['subject_id'] ?>">
                                            <img src="../images/delete.png" alt="delete" class="delete-sub">
                                        </button>
                                        <button class="btn-edit">
                                            <a
                                                href=<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?sub_id=" . $subjects['subject_id']) ?>><img
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
                                        <form action='../ajax/delete_sub.php' method="post" id="sub-delete">
                                            <input type="text" id="sub-id" name="sub_id" hidden>
                                            <button id="delete-sub" name="delete_sub" value="delete_sub"
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

                        <form id="edit-subform">

                            <input type="text" name="sub_id" id="sub_id" value="<?php echo $result['subject_id'] ?>"
                                hidden>

                            <div class="input-container">
                                <div class="input-body" id="code-body">
                                    <label for="code">Subject Code</label>
                                    <input type="text" name="code" id="code" value="<?php echo $result['code'] ?>">
                                </div>

                                <div class="input-body" id="sub-body">
                                    <label for="subject">Subject</label>
                                    <input type="text" name="subject" id="subject"
                                        value="<?php echo $result['subject'] ?>">
                                </div>

                                <div class="input-body" id="description-body">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="10"
                                        id="description"><?php echo $result['description'] ?></textarea>
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