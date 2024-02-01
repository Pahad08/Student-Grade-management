<?php

session_start();
if (!isset($_SESSION['students_id'])) {
    header("location: login.php");
} else {
    $acc_id = $_SESSION['students_id'];
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';
}

$controller = new controller("localhost", "root", "", "school");
$get_grade = $controller->GetGrades($acc_id);
$average = $controller->GetAverage($acc_id);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <title>Grades</title>
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
                <p>Teacher</p>
            </div>

            <hr>

            <nav id="nav-bar" class="nav-bar">

                <ul class="dashboard">
                    <li> <a href="student.php">Account</a></li>
                </ul>

                <ul class="sections">
                    <li> <a href="view_grades.php" class="active">Sections</a></li>
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
                    <h1>Grades</h1>
                </div>

                <hr>

                <div class="main-body">

                    <div class="table-container">

                        <div class="table-body" style="padding: 4px;">

                            <table id="table">

                                <tr class="row">
                                    <th class="table-head">Subject</th>
                                    <th class="table-head">Grade</th>
                                    <th class="table-head">Average</th>
                                </tr>
                                <?php while ($grade = $get_grade->fetch_assoc()) { ?>
                                <tr class="row">
                                    <td class="data">
                                        <p style="font-weight: bold;"><?php echo $grade['subject'] ?></p>
                                    </td>
                                    <td class="data">
                                        <p style="font-weight: bold;"><?php echo $grade['grade'] ?></p>
                                    </td>
                                </tr>
                                <?php } ?>

                                <tr class="row">
                                    <td class="data"> </td>
                                    <td class="data"> </td>
                                    <td class="data">
                                        <p style="font-weight: bold;"><?php echo $average ?></p>
                                    </td>
                                </tr>
                            </table>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </body>

    <script src="../js/index.js"></script>
    <script src="../js/nav.js"></script>

</html>