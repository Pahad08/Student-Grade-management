<?php
session_start();

if (!isset($_SESSION['teachers_id'])) {
    header("location: login.php");
} else {
    $acc_id = $_SESSION['teachers_id'];
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    require_once $root . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    echo "<script>alert('$message')</script>";
    unset($_SESSION['message']);
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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
                            <form action="../ajax/edit_profile.php" method="post" enctype="multipart/form-data" class="form-img">
                                <input type="text" value="teachers" name="teachers" hidden>
                                <input type="number" value="<?php echo $acc_id ?>" name="teacher_id" hidden>
                                <input type="file" name="image" id="image" accept="image/*" required>
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
                                <button id="btn-editinfo" class="btn-editinfo">Edit Profile</button>
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
                            <button id="btn-acc" class="btn-editinfo">Edit Account</button>
                            <button class="btn-editinfo" id="btn-pass">Change Password</button>
                        </div>

                    </div>


                </div>

            </div>

        </div>


        <div class="personal-form">
            <form action="../ajax/edit_personal.php" id="personal-form" method="post">

                <div class="form-header">
                    <h3>Personal Info</h3>
                    <p class="close">&#10006;</p>
                </div>

                <input type="text" name="teacher_id" value="<?php echo $teacher['teacher_id'] ?>" hidden>

                <div class="body-inputs">

                    <div class="input-containers" id="fname-containers">
                        <label for="fname">
                            <h4>First Name</h4>
                        </label>
                        <input type="text" name="fname" id="fname" value="<?php echo $teacher['f_name'] ?>" required>
                    </div>

                    <div class="input-containers" id="lname-containers">
                        <label for="lname">
                            <h4>Last Name</h4>
                        </label>
                        <input type="text" name="lname" id="lname" value="<?php echo $teacher['l_name']   ?>" required>
                    </div>

                    <div class="input-containers" id="lname-containers">
                        <label for="gender">
                            <h4>Gender</h4>
                        </label>
                        <select name="gender" id="gender">
                            <option value="<?php echo ($teacher['gender'] == "M") ? "M" : "F" ?>">
                                <?php echo ($teacher['gender'] == "M") ? "Male" : "Female" ?>
                            </option>
                            <option value=<?php echo ($teacher['gender'] == "M") ? "F" : "M" ?>>
                                <?php echo ($teacher['gender'] == "M") ? "Female" : "Male" ?>
                            </option>
                        </select>
                    </div>

                    <div class="edit-btns">
                        <button id="edit-personal">Edit</button>
                        <input type="reset" id="reset-personal">
                    </div>

                </div>

            </form>
        </div>

        <div class="account-form">
            <form action="../ajax/edit_account.php" id="account-form" method="post">

                <div class="form-header">
                    <h3>Account Info</h3>
                    <p class="close">&#10006;</p>
                </div>

                <input type="text" name="teacher_id" value="<?php echo $teacher['teacher_id'] ?>" hidden>

                <div class="body-inputs">

                    <div class="input-containers" id="username-body">
                        <label for="username">
                            <h4>Username</h4>
                        </label>
                        <input type="text" name="username" id="username" value="<?php echo $teacher['username'] ?>" required>
                    </div>

                    <div class="input-containers" id="email-body">
                        <label for="email">
                            <h4>Email</h4>
                        </label>
                        <input type="email" name="email" id="email" value="<?php echo $teacher['email'] ?>" required>
                    </div>

                    <div class="edit-btns">
                        <button id="edit-account">Edit</button>
                        <input type="reset" id="reset-acc">
                    </div>

                </div>

            </form>
        </div>

        <div class="password-form">
            <form action="../ajax/edit_pass.php" id="password-form" method="post">

                <div class="form-header">
                    <h3>Change Password</h3>
                    <p class="close">&#10006;</p>
                </div>

                <input type="text" name="teacher_id" value="<?php echo $teacher['teacher_id'] ?>" hidden>

                <div class="body-inputs">

                    <div class="input-containers" id="password-body">
                        <label for="curr-pass">
                            <h4>Current Password</h4>
                        </label>
                        <input type="password" name="curr-pass" id="curr-pass" required>
                    </div>

                    <div class="input-containers" id="newpass-body">
                        <label for="new-pass">
                            <h4>New Password</h4>
                        </label>
                        <input type="password" name="new-pass" id="new-pass" required>
                    </div>

                    <div class="input-containers" id="retype-body">
                        <label for="retype-pass">
                            <h4>Retype New Password</h4>
                        </label>
                        <input type="password" id="retype-pass" required>
                    </div>

                    <div class="edit-btns">
                        <button id="edit-pass">Change Password</button>
                    </div>

                </div>

            </form>
        </div>

    </div>

    </div>


    </div>

</body>

<script src="../js/index.js"></script>
<script src="../js/nav.js"></script>
<script>
    const form_img = document.querySelector(".form-img");
    const user_profile = document.querySelector(".user-profile");
    <?php $imagePath = json_encode($teacher['profile_pic']);
    echo $imagePath ?>
    const default_img = <?php echo $imagePath ?>;
    const dot = default_img.substring(0, 2);
    const folder = default_img.substring(2, 14);
    const file_name = default_img.substring(14);

    form_img.addEventListener("reset", () => {
        user_profile.src = `${dot}${folder}${file_name}`;
    })
</script>

</html>