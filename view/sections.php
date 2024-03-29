<?php

session_start();

if (!isset($_SESSION['teachers_id'])) {
    header("location: login.php");
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
    <title>Sections</title>
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
                <li> <a href="teacher.php">Account</a></li>
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
                <h1>Sections</h1>
            </div>

            <hr>

            <div class="main-body">

                <div class="table-container">

                    <div class="table-body" style="padding: 4px;">

                        <table id="table">

                            <tr class="row">
                                <th class="table-head">Grade 7</th>
                                <th class="table-head">Grade 8</th>
                                <th class="table-head">Grade 9</th>
                                <th class="table-head">Grade 10</th>
                                <th class="table-head">Grade 11</th>
                                <th class="table-head">Grade 12</th>
                            </tr>

                            <tr class="row">
                                <td class="data"><a href="grade_lvl/grade_7.php?sec=A" class="sec">A</a></td>
                                <td class="data"><a href="grade_lvl/grade_8.php?sec=A" class="sec">A</a></td>
                                <td class="data"><a href="grade_lvl/grade_9.php?sec=A" class="sec">A</a></td>
                                <td class="data"><a href="grade_lvl/grade_10.php?sec=A" class="sec">A</a></td>
                                <td class="data"><a href="grade_lvl/grade_11.php?sec=A" class="sec">A</a></td>
                                <td class="data"><a href="grade_lvl/grade_12.php?sec=A" class="sec">A</a></td>
                            </tr>

                            <tr class="row">
                                <td class="data"><a href="grade_lvl/grade_7.php?sec=B" class="sec">B</a></td>
                                <td class="data"><a href="grade_lvl/grade_8.php?sec=B" class="sec">B</a></td>
                                <td class="data"><a href="grade_lvl/grade_9.php?sec=B" class="sec">B</a></td>
                                <td class="data"><a href="grade_lvl/grade_10.php?sec=B" class="sec">B</a></td>
                                <td class="data"><a href="grade_lvl/grade_11.php?sec=B" class="sec">B</a></td>
                                <td class="data"><a href="grade_lvl/grade_12.php?sec=B" class="sec">B</a></td>
                            </tr>

                            <tr class="row">
                                <td class="data"><a href="grade_lvl/grade_7.php?sec=C" class="sec">C</a></td>
                                <td class="data"><a href="grade_lvl/grade_8.php?sec=C" class="sec">C</a></td>
                                <td class="data"><a href="grade_lvl/grade_9.php?sec=C" class="sec">C</a></td>
                                <td class="data"><a href="grade_lvl/grade_10.php?sec=C" class="sec">C</a></td>
                                <td class="data"><a href="grade_lvl/grade_11.php?sec=C" class="sec">C</a></td>
                                <td class="data"><a href="grade_lvl/grade_12.php?sec=C" class="sec">C</a></td>
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