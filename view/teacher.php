<?php
session_start();

if (!isset($_SESSION['teachers_id'])) {
    header("location: login.php");
} else {
    $acc_id = $_SESSION['teachers_id'];
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';
}

$controller = new controller("localhost", "root", "", "school");
$teacher_info = $controller->GetTeacher($acc_id);
$teacher = $teacher_info->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <title>Teacher</title>
    </head>

    <body>

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
                    <li> <a href="teacher.php" class="active">Account</a></li>
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
                    <h1>Account Info</h1>
                </div>

                <hr>

                <div class="main-body">

                    <div class="info-body">

                        <div id="user-info">

                            <div class="profile-container">
                                <img src="<?php echo $teacher['profile_pic'] ?>" class="user-profile">
                                <form action="" method="post" enctype="multipart/form-data" class="form-img">
                                    <input type="file" name="image" id="image" accept="image/*">
                                    <button name="btn" id="btn-file" value="btn">Edit Profile</button>
                                    <input type="reset" value="Reset" id="reset">
                                </form>
                            </div>

                            <div class="personal-details">

                                <div class="details-header">
                                    <h3>Personal Info</h3>
                                </div>

                                <div class="f_name">
                                    <h4>First Name</h4>
                                    <p><?php echo $teacher['f_name'] ?></p>
                                </div>

                                <div class="l_name">
                                    <h4>Last Name</h4>
                                    <p><?php echo $teacher['l_name'] ?></p>
                                </div>

                                <div class="gender">
                                    <h4>Gender</h4>
                                    <p><?php echo ($teacher['gender'] == "M") ? "Male" : "Female" ?></p>
                                </div>

                                <div class="edit-btnacc">
                                    <button id="btn-edit" class="btn-editacc">Edit Profile</button>
                                </div>

                            </div>

                        </div>

                        <div class="account-details">

                            <div class="account-header">
                                <h3>Account Details</h3>
                            </div>

                            <div class="username">
                                <h4>UserName</h4>
                                <p><?php echo $teacher['username'] ?></p>
                            </div>

                            <div class="email">
                                <h4>Email</h4>
                                <p><?php echo $teacher['email'] ?></p>
                            </div>

                            <div class="edit-btnacc">
                                <button id="btn-edit" class="btn-editacc">Edit Profile</button>
                                <button class="btn-editacc">Change Password</button>
                            </div>

                        </div>


                    </div>

                </div>


            </div>


            <!-- <div class="form-body" style="display: none;">
                    <form action="../ajax/edit_teacher.php" id="account-form">

                        <div class="input-containers">

                            <div class="basic-info">
                                <div class="input-body" id="fname-body">
                                    <label for="fname">First Name</label>
                                    <input type="text" name="fname" id="fname" value="<?php echo $teacher['f_name'] ?>">
                                </div>

                                <div class="input-body" id="lname-body">
                                    <label for="lname">Last Name</label>
                                    <input type="text" name="lname" id="lname"
                                        value="<?php echo $teacher['l_name']   ?>">
                                </div>

                                <div class="input-body" id="lname-body">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender">
                                        <option value="<?php echo ($teacher['gender'] == "M") ? "M" : "F" ?>">
                                            <?php echo ($teacher['gender'] == "M") ? "Male" : "Female" ?>
                                        </option>
                                        <option value=<?php echo ($teacher['gender'] == "M") ? "F" : "M" ?>>
                                            <?php echo ($teacher['gender'] == "M") ? "Female" : "Male" ?>
                                        </option>
                                    </select>
                                    <input type="text" name="lname" id="lname" value="<?php  ?>">
                                </div>

                            </div>

                            <div class="acc-info">

                                <div class="input-body" id="username-body">
                                    <label for="username"> Username</label>
                                    <input type="text" name="username" id="username" value="<?php  ?>">
                                </div>

                                <div class="input-body" id="password-body">
                                    <label for="fname">First Name</label>
                                    <input type="text" name="fname" id="fname" value="<?php  ?>">
                                </div>

                            </div>

                        </div>

                    </form>
                </div> -->

        </div>

        </div>


        </div>

    </body>

    <script src="../js/index.js"></script>
    <script src="../js/nav.js"></script>
    <script>
    const form_img = document.querySelector(".form-img");
    const user_profile = document.querySelector(".user-profile");
    const default_img = <?php echo '"' . $teacher['profile_pic'] . '"' ?>;
    const dot = default_img.substring(0, 2) + '\\';
    const folder = default_img.substring(2, 14);
    const file_name = default_img.substring(14)

    form_img.addEventListener("reset", () => {

        user_profile.src = `${dot}${folder}\\${file_name}`;
    })
    </script>

</html>