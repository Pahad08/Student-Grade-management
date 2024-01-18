<?php

session_start();

if (!isset($_SESSION['admins_id'])) {
    header("location: login.php");
} else {
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';
}

$controller = new controller("localhost", "root", "", "school");
$teachers_count = $controller->TeachersCount()->fetch_assoc();
$teachers_total = $teachers_count['total'];

$students_count = $controller->StudentsCount()->fetch_assoc();
$students_total = $students_count['total'];

$subject_count = $controller->SubjectCount()->fetch_assoc();
$subject_total = $subject_count['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Dashboard</title>
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
                <li> <a href="admin.php" class="active">Dashboard</a></li>
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
                <h1>Dashboard</h1>
            </div>

            <hr>

            <div class="main-body dashboard">

                <div class="cards">
                    <div class="info-card">
                        <img src="../images/people.png" alt="students">
                        <p><?php echo $students_total ?> <br> <span>Students</span></p>
                    </div>

                    <div class="info-card">
                        <img src="../images/people.png" alt="students">
                        <p><?php echo $subject_total ?> <br> <span>Subjects</span></p>
                    </div>

                    <div class="info-card">
                        <img src="../images/people.png" alt="students">
                        <p><?php echo $teachers_total ?> <br> <span>Teachers</span></p>
                    </div>
                </div>


                <div class="graph-body">

                    <div class="graph">
                        <div id="student-chart" style="max-width:900px;"></div>
                    </div>

                    <div class="graph">
                        <div id="teacher-chart" style="max-width:900px;"></div>
                    </div>

                </div>


            </div>


        </div>

    </div>

</body>

<script src="../js/admin.js"></script>
<script src="../js/nav.js"></script>
<script>
    const student_chart = document.querySelector("#student-chart");
    const teacher_chart = document.querySelector("#teacher-chart");

    //graphs
    if (student_chart) {
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Set Data
            const data = google.visualization.arrayToDataTable([
                ["Contry", "Mhl"],
                ["Italy", 54.8],
                ["France", 48.6],
                ["Spain", 44.4],
                ["USA", 23.9],
                ["Argentina", 14.5],
            ]);

            // Set Options
            const options = {
                title: "World Wide Wine Production",
            };

            // Draw
            const chart = new google.visualization.PieChart(student_chart);
            chart.draw(data, options);
        }
    }

    if (teacher_chart) {
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Set Data
            const data = google.visualization.arrayToDataTable([
                ["Contry", "Mhl"],
                ["Italy", 54.8],
                ["France", 48.6],
                ["Spain", 44.4],
                ["USA", 23.9],
                ["Argentina", 14.5],
            ]);

            // Set Options
            const options = {
                title: "World Wide Wine Production",
            };

            // Draw
            const chart = new google.visualization.PieChart(teacher_chart);
            chart.draw(data, options);
        }
    }
</script>

</html>