<?php

session_start();

if (!isset($_SESSION['admins_id'])) {
    header("location: login.php");
} else {
    require_once __DIR__;
}

if (!isset($_GET['page_num']) || $_GET['page_num'] <= 0) {
    $_GET['page_num'] = 1;
}

$page_num = $_GET['page_num'];
$num_perpage = 5;
$next_page =  $_GET['page_num'] + 1;
$prev_page =  $_GET['page_num'] - 1;
$offset = ($page_num * $num_perpage) - $num_perpage;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <title>Subjects</title>
</head>

<body>

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
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="view_students.php">Add
                            Students</a>
                    </li>
                </ul>

                <ul>
                    <li> <img src="../images/arrow.png" alt="arrow" class="arrow"><a href="add_students.php">View
                            Students</a>
                    </li>
                </ul>
            </ul>

            <ul class="sections">
                <li> <a href="sections.php">Sections</a></li>
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
                <h1>Subjects</h1>
            </div>

            <hr>

            <div class="main-body">

                <div class="table-container">

                    <div class="table-header">
                        <a href="add_subject.php">Add New</a>
                    </div>

                    <div class="search-bar">
                        <label for="search">Search: </label>
                        <input type="text" id="search">
                    </div>

                    <table id="table">

                        <tr>
                            <th>Code</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>

                        <?php while ($result_row = $result->fetch_assoc()) { ?>
                            <tr>

                                <td><?php echo $result_row['code']  ?></td>
                                <td><?php echo $result_row['subject']  ?></td>
                                <td><?php echo $result_row['description']  ?></td>
                                <td><button id="btn-delete" data-id="<?php echo $result_row['subject_id'] ?>"><img src="images/delete.png" alt="delete" data-id="<?php echo $result_row['subject_id'] ?>"></button>
                                    <button id="btn-edit" data-id="<?php echo $result_row['subject_id'] ?>"><img src="images/edit.png" alt="Edit"></button>
                                </td>

                            </tr>
                        <?php } ?>

                    </table>
                </div>

                <div class="pagination">

                    <ul id="previous">
                        <li><a <?php echo ($page_num == 1) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$prev_page}") ?>>Previous</a>
                        </li>
                    </ul>

                    <ul class="pages">
                        <ul></ul>
                    </ul>

                    <ul id="next">
                        <li><a <?php echo ($page_num == $total_pages) ? "" : "href=" . htmlspecialchars($_SERVER['PHP_SELF'] . "?page_num={$next_page}"); ?>>Next</a>
                        </li>
                    </ul>

                </div>
            </div>


        </div>

    </div>

</body>

<script src="../js/admin.js"></script>
<script src="../js/nav.js"></script>

</html>