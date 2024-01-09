<?php

declare(strict_types=1);
require '../model/model.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

class controller
{
    private $model;

    public function __construct($host, $username, $password, $dbname)
    {
        $this->model = new Model($host, $username, $password, $dbname);
    }

    //Close the connection
    public function CloseDB()
    {
        $conn = $this->model->getDb();
        return $conn->close();
    }

    //Sanitizing and cleaning data
    public function CleanData(mysqli $conn, string $data): string
    {
        $data = stripslashes($data);
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = mysqli_real_escape_string($conn, $data);
        return $data;
    }

    //redirect the user to a page
    public function redirect(mysqli $conn, string $location, int $user_id, string $usertype): void
    {
        session_regenerate_id(true);
        $_SESSION[$usertype . "_id"] = $user_id;
        $conn->close();
        header("location: ../view/" . $location);
        exit();
    }

    //Check the user data
    public function login(string $usertype, string $username, string $password): string
    {
        $conn = $this->model->getDb();

        switch ($usertype) {
            case 'admins':

                $user =  $this->model->SelectUser($usertype, $username);
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
                $username = ($user->num_rows > 0) ? $user_info['username'] : "";
                $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

                if ($user->num_rows > 0 && password_verify($this->CleanData($conn, $password), $hashed_pass)) {
                    $this->redirect($conn, "admin.php", $user_id, $usertype);
                } else {
                    $conn->close();
                    return "Incorrect Username or Password";
                }

                break;

            case 'students':

                $user =  $this->model->SelectUser($usertype, $username);
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
                $username = ($user->num_rows > 0) ? $user_info['username'] : "";
                $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

                if ($user->num_rows > 0 && password_verify($this->CleanData($conn, $password), $hashed_pass)) {
                    $this->redirect($conn, "student.php", $user_id, $usertype);
                } else {
                    $conn->close();
                    return "Incorrect Username or Password";
                }

                break;
        }
    }

    //Check email if exist and it will send OTP to the email
    public function SelectEmail(string $email): string
    {
        $result = $this->model->SelectEmail($email);

        if ($result == 0) {
            return 'error';
        } else {

            $token = bin2hex(random_bytes(32));
            $expiration_date = date('Y-m-d H:i:s', time() + (5 * 60));
            $this->model->InsertToken($token, $expiration_date, $email);
            $base_url = ($_SERVER['HTTP_HOST'] == "localhost") ? "localhost/studentmanagement" : $_SERVER['HTTP_HOST'] . ".com";
            $resetLink =  $base_url . "/view/reset_password.php?token=" . urlencode($token);

            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mastahpahad@gmail.com';
                $mail->Password   = 'gaocjcwwezyylzvk';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('mastahpahad@gmail.com');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Reset Password';
                $mail->Body =  "<a href='$resetLink'>Click here to reset your password</a>";
                if ($mail->send()) {
                    return 'success';
                }
            } catch (Exception $e) {
                return 'fail';
            }
        }
    }

    //Get subject
    public function GetSubjects(int $num_perpage, int $offset): mysqli_result
    {
        return $this->model->SelectSubjects($num_perpage, $offset);
    }

    //Get Specific Subject
    public function GetSubject(int $sub_id): mysqli_result
    {
        return $this->model->SelectSubject($sub_id);
    }

    //Get all total pages
    public function GetTotalpages(int $num_perpage): float
    {
        return $this->model->TotalPages($num_perpage);
    }

    //Select Token
    public function SelectToken(): mixed
    {
        return $this->model->SelectToken();
    }

    //Reset Password
    public function ResetPass(string $token, string $new_pass): string
    {
        if ($this->model->ResetPass($token, $new_pass)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Add subject
    public function AddSubject(string $code, string $subject, string $description): string
    {
        $conn = $this->model->getDb();

        if ($this->model->AddSubject(
            $this->CleanData($conn, $code),
            $this->CleanData($conn, $subject),
            $this->CleanData($conn, $description)
        )) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Delete Subject
    public function DeleteSub(int $id): string
    {
        if ($this->model->DeleteSubject($id)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Edit Subject
    public function EditSub(string $code, string $subject, string $description, int $sub_id): string
    {
        if ($this->model->EditSubject($code, $subject, $description, $sub_id)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Search Subjects
    public function SearchSubjects(string $subject): mysqli_result
    {
        $conn = $this->model->getDb();
        return $this->model->SearchSubjects($this->CleanData($conn, $subject));
    }

    //Select Students
    public function SelectStudents(int $num_perpage, int $offset): mysqli_result
    {
        return $this->model->SelectStudents($num_perpage, $offset);
    }

    //Add Student
    public function AddingStudent(
        string $username,
        string  $email,
        string  $fname,
        string  $lname,
        string $number,
        string $section,
        string $g_level,
        array $pic
    ): string {
        $conn = $this->model->getDb();
        $username = $this->CleanData($conn, $username);
        $email = $this->CleanData($conn, $email);
        $fname = $this->CleanData($conn, $fname);
        $lname = $this->CleanData($conn, $lname);
        $number = $this->CleanData($conn, $number);
        $section = $this->CleanData($conn, $section);
        $g_level = $this->CleanData($conn, $g_level);
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        if (empty($pic['size'])) {
            $profile_pic = $path . 'user.png';
        } else {
            $profile_pic = $path . $this->CleanData($conn, $pic['name']);
        }
        $add_student = $this->model->AddStudent(
            $username,
            $email,
            $fname,
            $lname,
            $number,
            $section,
            $g_level,
            $profile_pic
        );

        if ($add_student === 'success' && move_uploaded_file($pic['tmp_name'], $path . $pic['name'])) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Select student profile picture
    public function SelectProfilePic(int $id): mysqli_result
    {
        return $this->model->SelectProfile($id);
    }

    public function DeleteStudent($id)
    {

        if ($this->model->Deletestudent($id)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Check if the student have a default profile
    public function CheckProfile(string $delete_student, string $user_profile): void
    {
        if ($user_profile == '../profile_pics/user.png') {
            if ($delete_student == 'success') {
                $_SESSION['deleted_student'] = 'Student Deleted!';
                header('location: ../view/view_students.php');
                exit();
            } else {
                $_SESSION['delete_err'] = 'Theres an error, please try again!';
                header('location: ../view/view_students.php');
                exit();
            }
        } else {
            if ($delete_student == 'success') {
                if (unlink($user_profile)) {
                    $_SESSION['deleted_student'] = 'Student Deleted!';
                    header('location: ../view/view_students.php');
                    exit();
                }
            } else {
                $_SESSION['delete_err'] = 'Theres an error, please try again!';
                header('location: ../view/view_students.php');
                exit();
            }
        }
    }

    //Get student
    public function GetStudent(int $id): mysqli_result
    {
        return $this->model->SelectStudent($id);
    }

    //Get student account
    public function GetAccount(int $id): mysqli_result
    {
        return $this->model->SelectAccount($id);
    }

    //Edit Student
    public function Editstudent(
        string $username,
        string $email,
        string $password,
        string $fname,
        string $lname,
        string $number,
        string $section,
        string $g_level,
        array $pic,
        int $id
    ): string {

        $conn = $this->model->getDb();
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        $username = $this->CleanData($conn, $username);
        $email = $this->CleanData($conn, $email);
        $password = $this->CleanData($conn, password_hash($password, PASSWORD_BCRYPT));
        $fname = $this->CleanData($conn, $fname);
        $lname = $this->CleanData($conn, $lname);
        $number = $this->CleanData($conn, $number);
        $section = $this->CleanData($conn, $section);
        $g_level = $this->CleanData($conn, $g_level);
        $editstudent = $this->model->EditStudent($fname, $lname, $number, $section, $g_level, $pic, $id);
        $editacc = $this->model->EditAcc($username, $email, $password, $id);

        if ($editstudent == 'success' && $editacc == 'success') {
            move_uploaded_file($pic['tmp_name'], $path . $pic['name']);
            return 'success';
        } elseif ($editacc == 'duplicate') {
            return 'duplicate';
        } elseif ($editstudent == 'fail' && $editacc == 'duplicate') {
            return 'fail';
        } else {
            return 'error';
        }
    }
}
