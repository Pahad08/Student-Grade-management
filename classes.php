<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
        <title>Admin</title>
    </head>

    <body>

        <div class="sidebar">

            <div class="logo-header">
                <img src="images/logo.png" alt="logo">
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

                <ul class="classes">
                    <li> <a href="classes.php" class="active">Classes</a></li>
                </ul>

                <ul class="subject">
                    <li><a href="subject.php">Subjects</a></li>
                </ul>

                <ul class="add-student">
                    <li>
                        <div class="dropdown-text">
                            <p>Students</p>
                            <img src="images/arrow.png" alt="arrow">
                        </div>

                        <ul class="dropdown">
                            <li><a href="view_students.php">Add Students</a></li>
                            <li><a href="add_students.php">View Students</a></li>
                        </ul>
                    </li>
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
                    <img src="images/menu.png" alt="menu">
                </div>
            </div>

            <div class="info">

                <div class="text">
                    <h1>Dashboard</h1>
                </div>

                <hr>

                <div class="dashboard-body">
                    <div class="info-card">
                        <img src="images/people.png" alt="students">
                        <p>Total Students: <br> 6</p>
                    </div>
                </div>

            </div>

        </div>


    </body>

</html>