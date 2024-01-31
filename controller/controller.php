<?php

declare(strict_types=1);
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require $root . 'model' . DIRECTORY_SEPARATOR . "model.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $root . 'PHPMailer\src\Exception.php';
require $root . 'PHPMailer\src\PHPMailer.php';
require $root . 'PHPMailer\src\SMTP.php';
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

            case 'teachers':

                $user =  $this->model->SelectUser($usertype, $username);
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['teacher_id'] : "";
                $username = ($user->num_rows > 0) ? $user_info['username'] : "";
                $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

                if ($user->num_rows > 0 && password_verify($this->CleanData($conn, $password), $hashed_pass)) {
                    $this->redirect($conn, "teacher.php", $user_id, $usertype);
                } else {
                    $conn->close();
                    return "Incorrect Username or Password";
                }

                break;

            case 'students':

                $user =  $this->model->SelectUser($usertype, $username);
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['student_id'] : "";
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
            $base_url = ($_SERVER['HTTP_HOST'] == "localhost") ? "localhost/studentgrademanagement" : $_SERVER['HTTP_HOST'] . ".com";
            $resetLink =  $base_url . "/view/reset_password.php?token=" . urlencode($token);

            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mastahpahad@gmail.com';
                $mail->Password   = 'azowoluzslbupajq';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('sample@email.com');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Reset Password';
                $mail->Body =  "<a href='$resetLink'>Click here to reset your password</a>";
                if ($mail->send()) {
                    return 'success';
                } else {
                    throw new Exception("Error");
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

    //Get all subjects
    public function SelectSubjects(): mysqli_result
    {
        return $this->model->GetSubjects();
    }

    //Get Specific Subject
    public function GetSubject(int $sub_id): mysqli_result
    {
        return $this->model->SelectSubject($sub_id);
    }

    //Get all total pages
    public function GetTotalpages(string $column, int $num_perpage, string $table, string $section = "", string  $glvl = ""): float
    {
        return $this->model->TotalPages($column, $num_perpage, $table, $section, $glvl);
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
        string $gender,
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
        $gender = $this->CleanData($conn, $gender);
        $number = $this->CleanData($conn, $number);
        $section = $this->CleanData($conn, $section);
        $g_level = $this->CleanData($conn, $g_level);
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        if (empty($pic['size'])) {
            $profile_pic = $path . 'user.png';
        } else {
            $profile_pic = $path . $this->CleanData($conn, $pic['name']);
        }
        // $user_image = (empty($pic['size'])) ? 
        $add_student = $this->model->AddStudent(
            $username,
            $email,
            $fname,
            $lname,
            $gender,
            $number,
            $section,
            $g_level,
            $profile_pic
        );

        if ($add_student === 'success' && move_uploaded_file($pic['tmp_name'], $path . $profile_pic)) {
            return 'success';
        } elseif ($add_student === 'success') {
            return 'success';
        } elseif ($add_student === 'duplicate') {
            return 'duplicate';
        } else {
            return 'fail';
        }
    }

    //Select student profile picture
    public function SelectProfilePic(int $id, string $table, string $column): mysqli_result
    {
        return $this->model->SelectProfile($id, $table, $column);
    }

    //Delete Account
    public function DeleteAccount(int $id, string $table, string  $column): string
    {

        if ($this->model->DeleteUser($id, $table, $column)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Check if the student have a default profile
    public function CheckProfile(string $delete_student, string $user_profile, string $filename, string $usertype): void
    {
        if ($user_profile == '..\profile_pics\user.png') {
            if ($delete_student == 'success') {
                $_SESSION['deleted_student'] = $usertype . ' Deleted!';
                header('location: ../view/' . $filename);
                exit();
            } else {
                $_SESSION['delete_err'] = 'Theres an error, please try again!';
                header('location: ../view/' . $filename);
                exit();
            }
        } else {
            if ($delete_student == 'success') {
                if (unlink($user_profile)) {
                    $_SESSION['deleted_student'] = $usertype . ' Deleted!';
                    header('location: ../view/' . $filename);
                    exit();
                }
            } else {
                $_SESSION['delete_err'] = 'Theres an error, please try again!';
                header('location:' . $filename);
                exit();
            }
        }
    }

    //Get student
    public function GetStudent(int $id): mysqli_result
    {
        return $this->model->SelectStudent($id);
    }

    //Edit Student
    public function Editstudent(
        string $username,
        string $email,
        string $fname,
        string $lname,
        string $gender,
        string $number,
        string $section,
        string $g_level,
        array $pic,
        int $id,
    ): string {

        $conn = $this->model->getDb();
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        $username = $this->CleanData($conn, $username);
        $email = $this->CleanData($conn, $email);
        $fname = $this->CleanData($conn, $fname);
        $lname = $this->CleanData($conn, $lname);
        $number = $this->CleanData($conn, $number);
        $section = $this->CleanData($conn, $section);
        $g_level = $this->CleanData($conn, $g_level);
        $editstudent = $this->model->EditStudent($fname, $lname, $gender, $number, $section, $g_level, $pic, $id, $username, $email);
        if ($editstudent == 'success') {
            move_uploaded_file($pic['tmp_name'], $path . $pic['name']);
            return 'success';
        } elseif ($editstudent == 'duplicate') {
            return 'duplicate';
        } else {
            return 'error';
        }
    }

    //Search Subjects
    public function SearchStudents(string $student): mysqli_result
    {
        $conn = $this->model->getDb();
        return $this->model->SearchStudents($this->CleanData($conn, $student));
    }

    //Select students based on the section
    public function GetStudents(int $num_perpage, int $offset, string $section, string $glvl): mysqli_result
    {
        return $this->model->GetStudents($num_perpage, $offset, $section, $glvl);
    }

    //Select student grades
    public function GetGrades(int $id): mysqli_result
    {
        return $this->model->GetGrades($id);
    }

    //Add grades
    public function AddGrade(int $student_id, int $grade, int $subject_id): string
    {

        if ($this->model->AddGrade($student_id,  $grade, $subject_id)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Delete grade
    public function DeleteGrade(int $grade_id): string
    {
        if ($this->model->Deletegrade($grade_id)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //Add Teacher
    public function AddingTeacher(
        string $username,
        string  $email,
        string  $fname,
        string  $lname,
        string $gender,
        array $pic
    ): string {
        $conn = $this->model->getDb();
        $username = $this->CleanData($conn, $username);
        $email = $this->CleanData($conn, $email);
        $fname = $this->CleanData($conn, $fname);
        $lname = $this->CleanData($conn, $lname);
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        if (empty($pic['size'])) {
            $profile_pic = $path . 'user.png';
        } else {
            $profile_pic = $path . $this->CleanData($conn, $pic['name']);
        }
        // $user_image = (empty($pic['size'])) ? 
        $add_teacher = $this->model->AddTeacher(
            $username,
            $email,
            $fname,
            $lname,
            $gender,
            $profile_pic
        );

        if ($add_teacher === 'success' && move_uploaded_file($pic['tmp_name'], $path . $profile_pic)) {
            return 'success';
        } elseif ($add_teacher === 'success') {
            return 'success';
        } elseif ($add_teacher === 'duplicate email') {
            return 'duplicate';
        } else {
            return 'fail';
        }
    }

    //Select Teachers
    public function SelectTeachers(int $num_perpage, int $offset): mysqli_result
    {
        return $this->model->SelectTeachers($num_perpage, $offset);
    }

    //Get Teachers
    public function GetTeacher(int $id): mysqli_result
    {
        return $this->model->SelectTeacher($id);
    }

    //Edit Teacher
    public function Editteacher(
        string $fname,
        string $lname,
        string $gender,
        array $pic,
        string $username,
        string $email,
        int $id
    ): string {

        $conn = $this->model->getDb();
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        $username = $this->CleanData($conn, $username);
        $email = $this->CleanData($conn, $email);
        $fname = $this->CleanData($conn, $fname);
        $lname = $this->CleanData($conn, $lname);
        $editteacher = $this->model->EditTeacher($fname, $lname, $gender, $pic, $username, $email, $id);

        if ($editteacher == 'success') {
            move_uploaded_file($pic['tmp_name'], $path . $pic['name']);
            return 'success';
        } elseif ($editteacher == 'duplicate') {
            return 'duplicate';
        } else {
            return 'error';
        }
    }

    //Search Teacher
    public function SearchTeacher(string $teacher): mysqli_result
    {
        $conn = $this->model->getDb();
        return $this->model->SearchTeacher($this->CleanData($conn, $teacher));
    }

    //total teachers
    public function TeachersCount(): mysqli_result
    {
        return $this->model->TeachersCount();
    }

    //total students
    public function StudentsCount(): mysqli_result
    {
        return $this->model->StudentsCount();
    }

    //total subjects
    public function SubjectCount(): mysqli_result
    {
        return $this->model->subjectscount();
    }

    //total students based on gender
    public function TotalStudents(): mysqli_result
    {
        return $this->model->TotalStudents();
    }

    //total students based on gender
    public function TotalTeachers(): mysqli_result
    {
        return $this->model->TotalTeachers();
    }

    //Average grade every year level
    public function AverageGrade(): mysqli_result
    {
        return $this->model->AverageGrade();
    }

    //change profile pic
    public function ChangeProfile(int $id, string $usertype, array $pic): string
    {
        $pic_name = $pic['name'];
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        $change_profile = $this->model->ChangeProfile($id, $usertype, $path . $pic_name);

        if ($change_profile == "success" && move_uploaded_file($pic['tmp_name'], $path . $pic_name)) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //change personal details
    public function ChangePersonal(
        int $id,
        string $usertype,
        string $fname,
        string $lname,
        string $gender,
        string $contact_number = "",
        string $section = "",
        string $grade_level = ""
    ): string {
        $change_personal = $this->model->ChangePersonal($id, $usertype, $fname, $lname, $gender, $contact_number, $section, $grade_level);

        if ($change_personal == "success") {
            return  'success';
        } else {
            return 'fail';
        }
    }

    //change account details
    public function ChangeAccount(int $id,  string $usertype, string $username, string $email): string
    {
        $change_account = $this->model->ChangeAccount($id, $usertype, $username, $email);

        if ($change_account == "success") {
            return  'success';
        } elseif ($change_account == 'duplicate') {
            return 'duplicate';
        } else {
            return 'fail';
        }
    }

    //change password
    public function ChangePassword(int $id, string $usertype, string $password, string $newpass): string
    {
        $change_pass = $this->model->ChangePassword($id, $usertype, $password, $newpass);

        if ($change_pass == "success") {
            return  'success';
        } elseif ($change_pass == 'wrong') {
            return 'wrong';
        } else {
            return 'fail';
        }
    }
}
