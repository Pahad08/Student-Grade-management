<?php

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

    public function CloseDB()
    {
        $conn = $this->model->getDb();
        return $conn->close();
    }

    public function CleanData($conn, $data)
    {
        $data = stripslashes($data);
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = mysqli_real_escape_string($conn, $data);
        return $data;
    }

    public function redirect($conn, $location, $user_id, $usertype)
    {
        session_regenerate_id(true);
        $_SESSION[$usertype . "_id"] = $user_id;
        $conn->close();
        header("location: ../view/" . $location);
        exit();
    }

    public function login($usertype, $username, $password)
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

    public function SelectEmail($email)
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

    public function GetSubjects($num_perpage, $offset)
    {
        return $this->model->SelectSubjects($num_perpage, $offset);
    }

    public function GetSubject($sub_id)
    {
        return $this->model->SelectSubject($sub_id);
    }

    public function GetTotalpages($num_perpage)
    {
        return $this->model->TotalPages($num_perpage);
    }

    public function SelectToken()
    {
        return $this->model->SelectToken();
    }

    public function ResetPass($token, $new_pass)
    {
        if ($this->model->ResetPass($token, $new_pass)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    public function AddSubject($code, $subject, $description)
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

    public function DeleteSub($id)
    {
        if ($this->model->DeleteSubject($id)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    public function EditSub($code, $subject, $description, $sub_id)
    {
        if ($this->model->EditSubject($code, $subject, $description, $sub_id)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    public function SearchSubjects($subject)
    {
        $conn = $this->model->getDb();
        return $this->model->SearchSubjects($this->CleanData($conn, $subject));
    }

    public function SelectStudents($num_perpage, $offset)
    {
        return $this->model->SelectStudents($num_perpage, $offset);
    }

    public function AddingStudent($username, $email, $fname, $lname, $number, $section, $g_level, $pic)
    {
        $conn = $this->model->getDb();
        $username = $this->CleanData($conn, $username);
        $email = $this->CleanData($conn, $email);
        $fname = $this->CleanData($conn, $fname);
        $lname = $this->CleanData($conn, $lname);
        $number = $this->CleanData($conn, $number);
        $section = $this->CleanData($conn, $section);
        $g_level = $this->CleanData($conn, $g_level);
        $path = '../profile_pics/';
        if (empty($pic['name'])) {
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
}
